<?php

//Pass the this object instead of 12 varibles in a function 
class test_stats {
	//0Back variables 
	public $avg_reaction_time_m_no_faces, $accuracy_m_no_faces;
	public $avg_reaction_time_m_faces, $accuracy_m_faces;
	
	//2back variables
	public $avg_reaction_time_aba_no_faces, $accuracy_aba_no_faces;
	public $avg_reaction_time_aba_faces, $accuracy_aba_faces;

	//Faces/NoFaces variables 
	public $avg_reaction_time_faces, $accuracy_faces;
	public $avg_reaction_time_no_faces, $accuracy_no_faces;


	/**
	 *	return the values for each value in an SQL formatted string in the 
	 *		VALUES(......._THIS IS WHAT IS BEING RETURNED_......)
	 */
	public function enumerate_values_SQL(){
		$this->make_numeric(); 
		return "$this->avg_reaction_time_m_no_faces, $this->accuracy_m_no_faces, $this->avg_reaction_time_m_faces, $this->accuracy_m_faces, 
		$this->avg_reaction_time_aba_no_faces, $this->accuracy_aba_no_faces, $this->avg_reaction_time_aba_faces, $this->accuracy_aba_faces,
		$this->avg_reaction_time_faces, $this->accuracy_faces, $this->avg_reaction_time_no_faces, $this->accuracy_no_faces";
	}

	/**
	 *	Make sure every value is numeric 
	 *
	 */
	public function make_numeric(){
		//0Back
		if(!is_numeric($this->avg_reaction_time_m_no_faces)) $this->avg_reaction_time_m_no_faces = -1;
		if(!is_numeric($this->accuracy_m_no_faces)) $this->accuracy_m_no_faces = -1;
		if(!is_numeric($this->avg_reaction_time_m_faces)) $this->avg_reaction_time_m_faces = -1;
		if(!is_numeric($this->accuracy_m_faces)) $this->accuracy_m_faces = -1;

		//2back
		if(!is_numeric($this->avg_reaction_time_aba_no_faces)) $this->avg_reaction_time_aba_no_faces = -1;
		if(!is_numeric($this->accuracy_aba_no_faces)) $this->accuracy_aba_no_faces = -1;
		if(!is_numeric($this->avg_reaction_time_aba_faces)) $this->avg_reaction_time_aba_faces = -1;
		if(!is_numeric($this->accuracy_aba_faces)) $this->accuracy_aba_faces = -1;

		//Faces-NoFaces
		if(!is_numeric($this->avg_reaction_time_faces)) $this->avg_reaction_time_faces = -1;
		if(!is_numeric($this->accuracy_faces)) $this->accuracy_faces = -1;
		if(!is_numeric($this->avg_reaction_time_no_faces)) $this->avg_reaction_time_no_faces = -1;
		if(!is_numeric($this->accuracy_no_faces)) $this->accuracy_no_faces = -1;
	}

	/**
	 *	Set all of the vars using a row from a mysql query that contains all of the variables 
	 *		Each value will be the key i.e. $this->avg_reaction_time_m_no_faces = $row['avg_reaction_time_m_no_faces']
	 */
	public function set_vars_SQL($row){
		//0back
		$this->avg_reaction_time_m_no_faces = $row['avg_reaction_time_m_no_faces'];
		$this->accuracy_m_no_faces = $row['accuracy_m_no_faces'];
		$this->avg_reaction_time_m_faces = $row['avg_reaction_time_m_faces'];
		$this->accuracy_m_faces = $row['accuracy_m_faces'];

		//2back
		$this->avg_reaction_time_aba_no_faces = $row['avg_reaction_time_aba_no_faces'];
		$this->accuracy_aba_no_faces = $row['accuracy_aba_no_faces'];
		$this->avg_reaction_time_aba_faces = $row['avg_reaction_time_aba_faces'];
		$this->accuracy_aba_faces = $row['accuracy_aba_faces'];

		//faces-noFaces
		$this->avg_reaction_time_faces = $row['avg_reaction_time_faces'];
		$this->accuracy_faces = $row['accuracy_faces'];
		$this->avg_reaction_time_no_faces = $row['avg_reaction_time_no_faces'];
		$this->accuracy_no_faces = $row['accuracy_no_faces'];
	}

	/**
	 *	Return a row of these values 
	 *
	 */
	public function enumerate_values_table_row(){
		return "
			<tr>
				<td>$this->avg_reaction_time_m_no_faces</td> 
				<td>$this->accuracy_m_no_faces</td> 
				<td>$this->avg_reaction_time_m_faces</td> 
				<td>$this->accuracy_m_faces</td> 

				<td>$this->avg_reaction_time_aba_no_faces</td> 
				<td>$this->accuracy_aba_no_faces</td> 
				<td>$this->avg_reaction_time_aba_faces</td> 
				<td>$this->accuracy_aba_faces</td> 

				<td>$this->avg_reaction_time_faces</td> 
				<td>$this->accuracy_faces</td> 
				<td>$this->avg_reaction_time_no_faces</td> 
				<td>$this->accuracy_no_faces</td> 
			</tr>
		";
	}
}

?>