<?php
require('fpdf.php');

// Stream handler to read from global variables
class VariableStream
{
    private $varname;
    private $position;

    function stream_open($path, $mode, $options, &$opened_path)
    {
        $url = parse_url($path);
        $this->varname = $url['host'];
        if(!isset($GLOBALS[$this->varname]))
        {
            trigger_error('Global variable '.$this->varname.' does not exist', E_USER_WARNING);
            return false;
        }
        $this->position = 0;
        return true;
    }

    function stream_read($count)
    {
        $ret = substr($GLOBALS[$this->varname], $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }

    function stream_eof()
    {
        return $this->position >= strlen($GLOBALS[$this->varname]);
    }

    function stream_tell()
    {
        return $this->position;
    }

    function stream_seek($offset, $whence)
    {
        if($whence==SEEK_SET)
        {
            $this->position = $offset;
            return true;
        }
        return false;
    }
    
    function stream_stat()
    {
        return array();
    }
}

class PDF_MemImage extends FPDF
{
    function __construct($orientation='P', $unit='mm', $format='A4')
    {
        parent::__construct($orientation, $unit, $format);
        //parent::__construct($orientation);
        // Register var stream protocol
        stream_wrapper_register('var', 'VariableStream');
    }

	// Page header
	function Header()
	{
		// Logo
		$this->Image('assets/files/fieldbook.png',10,6,50);
		// Arial bold 15
		//$this->SetFont('Arial','B',15);
		// Move to the right
		//$this->Cell(80);
		// Title
		//$this->Cell(30,10,'Title',0,0,'C');
		// Line break
		$this->Ln(1);
	}

	// Page footer
	function Footer()
	{
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Page number
		$this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
	}

    function MemImage($data, $x=null, $y=null, $w=0, $h=0, $link='')
    {
        // Display the image contained in $data
        $v = 'img'.md5($data);
        $GLOBALS[$v] = $data;
        $a = getimagesize('var://'.$v);
        if(!$a)
            $this->Error('Invalid image data');
        $type = substr(strstr($a['mime'],'/'),1);
        $this->Image('var://'.$v, $x, $y, $w, $h, $type, $link);
        unset($GLOBALS[$v]);
    }

    function GDImage($im, $x=null, $y=null, $w=0, $h=0, $link='')
    {
        // Display the GD image associated with $im
        ob_start();
        imagepng($im);
        $data = ob_get_clean();
        $this->MemImage($data, $x, $y, $w, $h, $link);
    }

	function spotTitle($spotname,$xpos=15){
	
		if($xpos){
			$this->SetX($xpos);
		}
		//$this->SetX(0);
		$this->SetFont('Arial','B',12);
		$this->cell(0,5,"Spot Name: $spotname",'',1,'L');
		$this->cell(0,2,'','',1,L);
	
	}
	
	function dayTitle($datestring,$xpos){
	
		if($xpos){
			$this->SetX($xpos);
		}
		//$this->SetX(0);
		$this->SetFont('Arial','B',14);
		$this->cell(0,5,"$datestring",'B',1,'L');
		$this->cell(0,3,'','',1,L);
	
	}

	function datasetTitle($datasetname,$thisdate){
	
		$this->SetFont('Arial','B',12);
		$this->Cell(null,null,"Dataset: $datasetname",0,2,'C');
		$this->Ln(5);
		if($thisdate){
			$this->SetFont('Arial','',10);
			$this->Cell(null,null,"uploaded: $thisdate",0,0,'C');
			$this->Ln(5);
		}
	
	}

	function valueTitle($text,$xpos){
	
		if($xpos){
			$this->SetX($xpos);
		}
		
		$width = $this->GetStringWidth($text);
		
		$this->SetFont('Arial','B',8);
		$this->cell($width,3,"$text",'B',1,'L');
		$this->Ln(1);

	}

	function valueRow($label,$value,$xpos){
	
		if($xpos){
			$this->SetX($xpos);
		}

		//$this->SetFont('Arial','B',8);
		//$this->cell(0,4,"$label:",'',0,'');
		$this->SetFont('Arial','B',8);
		$this->cell(0,3,"$label: $value",'',1,'L');
		//$this->Ln(5);

	}
	
	function notesRow($label,$value,$xpos){
	
		$this->Ln(1);
	
		if($xpos){
			$this->SetX($xpos);
		}
		
		//$height = $numrows * 1;
		
		$height = 100;
		
		//$this->SetFont('Arial','B',8);
		//$this->cell(0,4,"$label:",'',0,'');
		$this->SetFont('Arial','B',8);
		$this->cell(0,5,"$label:",'',1,'L');
		if($xpos){
			$this->SetX($xpos);
		}
		$this->MultiCell( 180, 4, "$value", 1);
		$this->Ln(1);
		//$this->Ln(5);

	}
	
	function imageCaptionRow($label,$value,$xpos){
	
		$this->Ln(1);
	
		if($xpos){
			$this->SetX($xpos);
		}
		
		//$height = $numrows * 1;
		
		$height = 100;
		
		//$this->SetFont('Arial','B',8);
		//$this->cell(0,4,"$label:",'',0,'');
		$this->SetFont('Arial','B',8);
		$this->cell(0,5,"$label:",'',1,'L');
		if($xpos){
			$this->SetX($xpos);
		}
		$this->MultiCell( 100, 4, "$value", 0);
		$this->Ln(1);
		//$this->Ln(5);

	}
	

	function dailyNotesRow($label,$value,$xpos){
	
		//$this->Ln(1);
	
		if($xpos){
			$this->SetX($xpos);
		}
		
		//$height = $numrows * 1;
		
		$height = 100;
		
		//$this->SetFont('Arial','B',8);
		//$this->cell(0,4,"$label:",'',0,'');
		$this->SetFont('Arial','B',8);
		$this->cell(0,5,"$label:",'',1,'L');
		if($xpos){
			$this->SetX($xpos+2);
		}
		$this->MultiCell( 190, 4, "$value", 1);
		$this->Ln(1);
		$this->Ln(1);
		//$this->Ln(5);

	}



	function WordWrap(&$text, $maxwidth)
	{
		$text = trim($text);
		if ($text==='')
			return 0;
		$space = $this->GetStringWidth(' ');
		$lines = explode("\n", $text);
		$text = '';
		$count = 0;

		foreach ($lines as $line)
		{
			$words = preg_split('/ +/', $line);
			$width = 0;

			foreach ($words as $word)
			{
				$wordwidth = $this->GetStringWidth($word);
				if ($wordwidth > $maxwidth)
				{
					// Word is too long, we cut it
					for($i=0; $i<strlen($word); $i++)
					{
						$wordwidth = $this->GetStringWidth(substr($word, $i, 1));
						if($width + $wordwidth <= $maxwidth)
						{
							$width += $wordwidth;
							$text .= substr($word, $i, 1);
						}
						else
						{
							$width = $wordwidth;
							$text = rtrim($text)."\n".substr($word, $i, 1);
							$count++;
						}
					}
				}
				elseif($width + $wordwidth <= $maxwidth)
				{
					$width += $wordwidth + $space;
					$text .= $word.' ';
				}
				else
				{
					$width = $wordwidth + $space;
					$text = rtrim($text)."\n".$word.' ';
					$count++;
				}
			}
			$text = rtrim($text)."\n";
			$count++;
		}
		$text = rtrim($text);
		return $count;
	}






















}
?>