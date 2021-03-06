<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * A model to retrieve shared data from the WACKY server.
 *
 * @author namblue
 */
class Wacky
{
    private $airports, $airlines, $airplanes, $regions;
    
    /*
     * Purpose: List the participating airlines
     * Returns: A list of active airlines, the data 
     * for an explicitly identified one, or else an error message 
     */
    public function airlines()
    {
        $response = file_get_contents('https://wacky.jlparry.com/info/airlines/');
        $this->airlines = json_decode($response, TRUE);
        return $this->airlines;
    }
    
    /*
     * Purpose: List the airports used by WAC 
     * Returns: A list of known airports, the data 
     * for an explicitly identified one, or an error message 
     */
    public function airports()
    {
        $response = file_get_contents('https://wacky.jlparry.com/info/airports/');
        $this->airports = json_decode($response, TRUE);
        return $this->airports;
    }
    
    /*
     * Purpose: List the aircraft recognized by WAC 
     * Returns: A list of allowed aircraft, the data 
     * for an explicitly identified one, or an error message 
     */
    public function airplanes()
    {
        $response = file_get_contents('https://wacky.jlparry.com/info/airplanes/');
        $this->airplanes = json_decode($response, TRUE);
        return $this->airplanes;
    }
    
    /*
     * Purpose: List the regions recognized by WAC 
     * Returns: A list of allowed regions, the data 
     * for an explicitly identified one, or an error message 
     */
    public function regions()
    {
        $response = file_get_contents('https://wacky.jlparry.com/info/regions/');
        $this->regions = json_decode($response, TRUE);
        return $this->regions;
    }
    
    /*
     * Purpose: List the manufacturers recognized by WAC 
     * Returns: A list of allowed manufacturers, the data 
     * for an explicitly identified one, or an error message 
     */
    public function manufacturers()
    {
        $manufacturers = array();
        if(!isset($this->airplanes))
            $this->airplanes();
        foreach($this->airplanes as $airplane)
        {
            $manufacturers[$airplane['manufacturer']] = $airplane['manufacturer'];
        }
        return $manufacturers;
    }
    
    /*
     * Purpose: List the models recognized by WAC 
     * Returns: A list of allowed models, the data 
     * for an explicitly identified one, or an error message 
     */
    public function models()
    {
        $models = array();
        if(!isset($this->airplanes))
            $this->airplanes();
        foreach($this->airplanes as $airplane)
        {
            $models[$airplane['model']] = $airplane['model'];
        }
        return $models;
    }

    /*
     * Purpose: List the airportIds recognized by WAC 
     * Returns: A list of allowed airportIds, the data 
     * for an explicitly identified one, or an error message 
     */
    public function airportIds()
    {
        $destinationIds = array();
        foreach($this->airports as $airport)
        {
            array_push($destinationIds, $airport['id']);
        }
        return $destinationIds;
    }
    
    /*
     * Purpose: List the airportNames recognized by WAC 
     * Returns: A list of allowed airportNames, the data 
     * for an explicitly identified one, or an error message 
     */
    public function airportNames()
    {
        $destinationAirports = array();
        foreach($this->airports as $airport)
        {
            array_push($destinationAirports, $airport['airport']);
        }
        return $destinationAirports;
    }
    
    /*
     * Purpose: Get the price of a plane recognized by WAC by manufacturer and model
     * Returns: The price of the specified plane or 0 if not found
     */
    public function getPrice($manufacturer, $model)
    {
        if(!isset($this->airplanes))
            $this->airplanes();
        foreach($this->airplanes as $airplane)
        {
            if($airplane['manufacturer'] == $manufacturer && $airplane['model'] == $model)
                return (int)$airplane['price'];
        }
        return 0;
    }
}
