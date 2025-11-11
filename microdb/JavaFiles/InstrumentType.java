package org.strabospot.datatypes;

public class InstrumentType {
    public String instrumentType;
    public String dataType;
    public String instrumentBrand;
    public String instrumentModel;
    public String university;
    public String laboratory;
    public String dataCollectionSoftware;
    public String dataCollectionSoftwareVersion;
    public String postProcessingSoftware;
    public String postProcessingSoftwareVersion;
    public String filamentType;
    public InstrumentDetectorType[] instrumentDetectors;
    public String instrumentNotes;
    public Double accelerationVoltage;
    public Double beamCurrent;
    public Double spotSize;
    public Double aperture;
    public Double cameraLength;
    public String cameraBinning;
    public Double analysisDwellTime;
    public Double dwellTime;
    public Double workingDistance;
    public Boolean instrumentPurged;
    public String instrumentPurgedGasType;
    public Boolean environmentPurged;
    public String environmentPurgedGasType;
    public Double scanTime;
    public Double resolution;
    public Double spectralResolution;
    public String wavenumberRange;
    public String averaging;
    public String backgroundComposition;
    public String backgroundCorrectionFrequencyAndNotes;
    public Double excitationWavelength;
    public Double laserPower;
    public Double diffractionGrating;
    public Double integrationTime;
    public Double objective;
    public String calibration;
    public String notesOnPostProcessing;
    public String atomicMode;
    public Double cantileverStiffness;
    public Double tipDiameter;
    public Double operatingFrequency;
    public String scanDimensions;
    public String scanArea;
    public Double spatialResolution;
    public Double temperatureOfRoom;
    public Double relativeHumidity;
    public Double sampleTemperature;
    public Double stepSize;
    public Double backgroundDwellTime;
    public InstrumentWDSStandardsType[] standards;
    public String backgroundCorrectionTechnique;
    public Double deadTime;
    public String calibrationStandardNotes;
    public String notesOnCrystalStructuresUsed;
    public String color;
    public String rgbCheck;
    public String energyLoss;

    public String getInstrumentType() {
        return instrumentType;
    }

    public void setInstrumentType(String instrumentType) {
        this.instrumentType = instrumentType;
    }

    public String getDataType() {
        return dataType;
    }

    public void setDataType(String dataType) {
        this.dataType = dataType;
    }

    public String getInstrumentBrand() {
        return instrumentBrand;
    }

    public void setInstrumentBrand(String instrumentBrand) {
        this.instrumentBrand = instrumentBrand;
    }

    public String getInstrumentModel() {
        return instrumentModel;
    }

    public void setInstrumentModel(String instrumentModel) {
        this.instrumentModel = instrumentModel;
    }

    public String getUniversity() {
        return university;
    }

    public void setUniversity(String university) {
        this.university = university;
    }

    public String getLaboratory() {
        return laboratory;
    }

    public void setLaboratory(String laboratory) {
        this.laboratory = laboratory;
    }

    public String getDataCollectionSoftware() {
        return dataCollectionSoftware;
    }

    public void setDataCollectionSoftware(String dataCollectionSoftware) {
        this.dataCollectionSoftware = dataCollectionSoftware;
    }

    public String getDataCollectionSoftwareVersion() {
        return dataCollectionSoftwareVersion;
    }

    public void setDataCollectionSoftwareVersion(String dataCollectionSoftwareVersion) {
        this.dataCollectionSoftwareVersion = dataCollectionSoftwareVersion;
    }

    public String getPostProcessingSoftware() {
        return postProcessingSoftware;
    }

    public void setPostProcessingSoftware(String postProcessingSoftware) {
        this.postProcessingSoftware = postProcessingSoftware;
    }

    public String getPostProcessingSoftwareVersion() {
        return postProcessingSoftwareVersion;
    }

    public void setPostProcessingSoftwareVersion(String postProcessingSoftwareVersion) {
        this.postProcessingSoftwareVersion = postProcessingSoftwareVersion;
    }

    public String getFilamentType() {
        return filamentType;
    }

    public void setFilamentType(String filamentType) {
        this.filamentType = filamentType;
    }

    public InstrumentDetectorType[] getInstrumentDetectors() {
        return instrumentDetectors;
    }

    public void setInstrumentDetectors(InstrumentDetectorType[] instrumentDetectors) {
        this.instrumentDetectors = instrumentDetectors;
    }

    public String getInstrumentNotes() {
        return instrumentNotes;
    }

    public void setInstrumentNotes(String instrumentNotes) {
        this.instrumentNotes = instrumentNotes;
    }

    public Double getAccelerationVoltage() {
        return accelerationVoltage;
    }

    public void setAccelerationVoltage(Double accelerationVoltage) {
        this.accelerationVoltage = accelerationVoltage;
    }

    public Double getBeamCurrent() {
        return beamCurrent;
    }

    public void setBeamCurrent(Double beamCurrent) {
        this.beamCurrent = beamCurrent;
    }

    public Double getSpotSize() {
        return spotSize;
    }

    public void setSpotSize(Double spotSize) {
        this.spotSize = spotSize;
    }

    public Double getAperture() {
        return aperture;
    }

    public void setAperture(Double aperture) {
        this.aperture = aperture;
    }

    public Double getCameraLength() {
        return cameraLength;
    }

    public void setCameraLength(Double cameraLength) {
        this.cameraLength = cameraLength;
    }

    public String getCameraBinning() {
        return cameraBinning;
    }

    public void setCameraBinning(String cameraBinning) {
        this.cameraBinning = cameraBinning;
    }

    public Double getDwellTime() {
        return dwellTime;
    }

    public void setDwellTime(Double dwellTime) {
        this.dwellTime = dwellTime;
    }

    public Double getAnalysisDwellTime() {
        return analysisDwellTime;
    }

    public void setAnalysisDwellTime(Double analysisDwellTime) {
        this.analysisDwellTime = analysisDwellTime;
    }

    public Double getWorkingDistance() {
        return workingDistance;
    }

    public void setWorkingDistance(Double workingDistance) {
        this.workingDistance = workingDistance;
    }

    public Boolean getInstrumentPurged() {
        return instrumentPurged;
    }

    public void setInstrumentPurged(Boolean instrumentPurged) {
        this.instrumentPurged = instrumentPurged;
    }

    public String getInstrumentPurgedGasType() {
        return instrumentPurgedGasType;
    }

    public void setInstrumentPurgedGasType(String instrumentPurgedGasType) {
        this.instrumentPurgedGasType = instrumentPurgedGasType;
    }

    public Boolean getEnvironmentPurged() {
        return environmentPurged;
    }

    public void setEnvironmentPurged(Boolean environmentPurged) {
        this.environmentPurged = environmentPurged;
    }

    public String getEnvironmentPurgedGasType() {
        return environmentPurgedGasType;
    }

    public void setEnvironmentPurgedGasType(String environmentPurgedGasType) {
        this.environmentPurgedGasType = environmentPurgedGasType;
    }

    public Double getScanTime() {
        return scanTime;
    }

    public void setScanTime(Double scanTime) {
        this.scanTime = scanTime;
    }

    public Double getResolution() {
        return resolution;
    }

    public void setResolution(Double resolution) {
        this.resolution = resolution;
    }

    public Double getSpectralResolution() {
        return spectralResolution;
    }

    public void setSpectralResolution(Double spectralResolution) {
        this.spectralResolution = spectralResolution;
    }

    public String getWavenumberRange() {
        return wavenumberRange;
    }

    public void setWavenumberRange(String wavenumberRange) {
        this.wavenumberRange = wavenumberRange;
    }

    public String getAveraging() {
        return averaging;
    }

    public void setAveraging(String averaging) {
        this.averaging = averaging;
    }

    public String getBackgroundComposition() {
        return backgroundComposition;
    }

    public void setBackgroundComposition(String backgroundComposition) {
        this.backgroundComposition = backgroundComposition;
    }

    public String getBackgroundCorrectionFrequencyAndNotes() {
        return backgroundCorrectionFrequencyAndNotes;
    }

    public void setBackgroundCorrectionFrequencyAndNotes(String backgroundCorrectionFrequencyAndNotes) {
        this.backgroundCorrectionFrequencyAndNotes = backgroundCorrectionFrequencyAndNotes;
    }

    public Double getExcitationWavelength() {
        return excitationWavelength;
    }

    public void setExcitationWavelength(Double excitationWavelength) {
        this.excitationWavelength = excitationWavelength;
    }

    public Double getLaserPower() {
        return laserPower;
    }

    public void setLaserPower(Double laserPower) {
        this.laserPower = laserPower;
    }

    public Double getDiffractionGrating() {
        return diffractionGrating;
    }

    public void setDiffractionGrating(Double diffractionGrating) {
        this.diffractionGrating = diffractionGrating;
    }

    public Double getIntegrationTime() {
        return integrationTime;
    }

    public void setIntegrationTime(Double integrationTime) {
        this.integrationTime = integrationTime;
    }

    public Double getObjective() {
        return objective;
    }

    public void setObjective(Double objective) {
        this.objective = objective;
    }

    public String getCalibration() {
        return calibration;
    }

    public void setCalibration(String calibration) {
        this.calibration = calibration;
    }

    public String getNotesOnPostProcessing() {
        return notesOnPostProcessing;
    }

    public void setNotesOnPostProcessing(String notesOnPostProcessing) {
        this.notesOnPostProcessing = notesOnPostProcessing;
    }

    public String getAtomicMode() {
        return atomicMode;
    }

    public void setAtomicMode(String atomicMode) {
        this.atomicMode = atomicMode;
    }

    public Double getCantileverStiffness() {
        return cantileverStiffness;
    }

    public void setCantileverStiffness(Double cantileverStiffness) {
        this.cantileverStiffness = cantileverStiffness;
    }

    public Double getTipDiameter() {
        return tipDiameter;
    }

    public void setTipDiameter(Double tipDiameter) {
        this.tipDiameter = tipDiameter;
    }

    public Double getOperatingFrequency() {
        return operatingFrequency;
    }

    public void setOperatingFrequency(Double operatingFrequency) {
        this.operatingFrequency = operatingFrequency;
    }

    public String getScanDimensions() {
        return scanDimensions;
    }

    public void setScanDimensions(String scanDimensions) {
        this.scanDimensions = scanDimensions;
    }

    public String getScanArea() {
        return scanArea;
    }

    public void setScanArea(String scanArea) {
        this.scanArea = scanArea;
    }

    public Double getSpatialResolution() {
        return spatialResolution;
    }

    public void setSpatialResolution(Double spatialResolution) {
        this.spatialResolution = spatialResolution;
    }

    public Double getTemperatureOfRoom() {
        return temperatureOfRoom;
    }

    public void setTemperatureOfRoom(Double temperatureOfRoom) {
        this.temperatureOfRoom = temperatureOfRoom;
    }

    public Double getRelativeHumidity() {
        return relativeHumidity;
    }

    public void setRelativeHumidity(Double relativeHumidity) {
        this.relativeHumidity = relativeHumidity;
    }

    public Double getSampleTemperature() {
        return sampleTemperature;
    }

    public void setSampleTemperature(Double sampleTemperature) {
        this.sampleTemperature = sampleTemperature;
    }

    public Double getStepSize() {
        return stepSize;
    }

    public void setStepSize(Double stepSize) {
        this.stepSize = stepSize;
    }

    public Double getBackgroundDwellTime() {
        return backgroundDwellTime;
    }

    public void setBackgroundDwellTime(Double backgroundDwellTime) {
        this.backgroundDwellTime = backgroundDwellTime;
    }

    public InstrumentWDSStandardsType[] getStandards() {
        return standards;
    }

    public void setStandards(InstrumentWDSStandardsType[] standards) {
        this.standards = standards;
    }

    public String getBackgroundCorrectionTechnique() {
        return backgroundCorrectionTechnique;
    }

    public void setBackgroundCorrectionTechnique(String backgroundCorrectionTechnique) {
        this.backgroundCorrectionTechnique = backgroundCorrectionTechnique;
    }

    public Double getDeadTime() {
        return deadTime;
    }

    public void setDeadTime(Double deadTime) {
        this.deadTime = deadTime;
    }

    public String getCalibrationStandardNotes() {
        return calibrationStandardNotes;
    }

    public void setCalibrationStandardNotes(String calibrationStandardNotes) {
        this.calibrationStandardNotes = calibrationStandardNotes;
    }

    public String getNotesOnCrystalStructuresUsed() {
        return notesOnCrystalStructuresUsed;
    }

    public void setNotesOnCrystalStructuresUsed(String notesOnCrystalStructuresUsed) {
        this.notesOnCrystalStructuresUsed = notesOnCrystalStructuresUsed;
    }

    public String getColor() {
        return color;
    }

    public void setColor(String color) {
        this.color = color;
    }

    public String getRgbCheck() {
        return rgbCheck;
    }

    public void setRgbCheck(String rgbCheck) {
        this.rgbCheck = rgbCheck;
    }

    public String getEnergyLoss() {
        return energyLoss;
    }

    public void setEnergyLoss(String energyLoss) {
        this.energyLoss = energyLoss;
    }
}
