<?php

abstract class Functions
{
	protected $all = array("add_three","multiply_by_ten","divide_by_two","divide_by_ten","multiply_by_third_part","cube","add_random","pow_random");
	protected $set1 = array("add_three","multiply_by_ten","divide_by_two");
	protected $set2 = array("multiply_by_third_part","cube","add_random","pow_random");
	protected $set3 = array("add_three","divide_by_two","multiply_by_third_part","add_random");

	protected function add_three($i)
	{
		return $i+3;
	}

	protected function multiply_by_ten($i)
	{
		return $i*10;
	} 

	protected function divide_by_two($i)
	{
		return intval($i/2);
	}

	protected function divide_by_ten($i)
	{
		return intval($i/10);
	}

	protected function multiply_by_third_part($i)
	{
		return $i*intval($i/3);
	}

	protected function cube($i)
	{
		return $i*$i*$i;
	}

	protected function add_random($i)
	{
		return $i+mt_rand(10,100);
	}

	protected function pow_random($i)
	{
		$limit = mt_rand(1,9);
		for ($j=2; $j <= $limit ; $j++) { 
			$i = $i*$i;
		}
		return $i;
	}
}

class OutputValues extends Functions
{
	protected $_class='Functions';
	protected $_methods=array();

	public function get($i,$set)
	{
		$ref = new ReflectionClass($this->_class);
		$_methods = $ref->getMethods();
		$current_set = array();

		switch($set)
		{
			case "all": $current_set = $this->all;
			break;

			case "set1": $current_set = $this->set1;
			break;

			case "set2": $current_set = $this->set2;
			break;

			case "set3": $current_set = $this->set3;
			break;
		}

		foreach ($_methods as $key) {
			$meth=$key->name;
			if(in_array($meth, $current_set))
			{
				echo " <br/> $meth: ";
				echo $this->$meth($i);
			}
		}
	}
}

?>

<html>
<body>

	<?php

	session_start(); 
	session_regenerate_id(); 

	if ( !isset( $_SESSION['token'] ) ) { 
		$token = sha1( uniqid( rand(), true ) ); 
		$_SESSION['token'] = $token; 
	}
	else $token = $_SESSION['token'];

	echo "<br /> <b>Output: </b>";

	if(!isset($_POST['token']))	$_POST['token']='';

	if($_POST['token']==$_SESSION['token'])
	{
		unset($_SESSION['token']);
		$token = sha1( uniqid( rand(), true ) ); 
		$_SESSION['token'] = $token;

		$set = $_POST['operatii'];
		$value=$_POST['in_value'];
		if(!is_numeric($value) || intval($value)!=$value || $value=="" || $set=="") // validare numar intreg
		{
			?>
			<p><font color="red"> Valoarea introdusa trebuie sa fie un numar intreg ! </font></p>
			<?php
		}
		else
		{
			$object = new OutputValues();
			$object->get($value,$set);
			echo "<br /><br />";
		}
	}

	?>

	<form action="collections.php" method="post">
		<label> Introduceti <b>i</b>: </label>
		<input type="text" name="in_value" maxlenght="4" />
		<br />
		<label> Alegeti operatiile: </label>
		<select name="operatii">
		  <option value="all">Toate</option>
		  <option value="set1">Set 1</option>
		  <option value="set2">Set 2</option>
		  <option value="set3">Set 3</option>
		</select>
		
		<br />
		<label><i>* Setul <b>Toate</b> contine toate functiile: "add_three","multiply_by_ten","divide_by_two","divide_by_ten",multiply_by_third_part","cube","add_random","pow_random" </i>
				<br /> <i>* Setul <b>Set1</b> contine functiile: "add_three","multiply_by_ten","divide_by_two" </i>
				<br /> <i>* Setul <b>Set2</b> contine functiile: "multiply_by_third_part","cube","add_random","pow_random" </i>
				<br /> <i>* Setul <b>Set3</b> contine functiile: "add_three","divide_by_two","multiply_by_third_part","add_random" </i>
		</label>

		<br />
		<input type="hidden" name="token" value="<?php echo $token; ?>" />
		<input type="submit" value="Submit" />
	</form>

</body>
</html>
