<?php
/*************************************************************************************************

Function Name: getNextGeneration
Input Parameters
	fn_input 	-- actual input string
	grid_row 	-- total number of rows of grid
	grid_col 	-- total number of column of grid
Return
	input_array -- next generation grid of game_of_life 
Description
	-This function stores the string in 2D array(4 X 8 grid) of live and dead cells.
		live cells are denoted by *
		dead cells are denoted by .
	-Calculates the next generation grid of game_of_life based on 4 conditions
		1. Any live cell with fewer than two live neighbours dies, as if caused by under population.
		2. Any live cell with more than three live neighbours dies, as if by overcrowding.
		3. Any live cell with two or three live neighbours lives on to the next generation.
		4. Any dead cell with exactly three live neighbours becomes a live cell.

*************************************************************************************************/

function getNextGeneration($fn_input, $grid_row, $grid_col)
{
	/* store the input in a grid form(2D array) */
	
	$input_array = array();
	for($m=0; $m<$grid_row; $m++)
	{
		for($n=0; $n<$grid_col; $n++)
		{
			$pos = $grid_col*$m+$n;	// calculate respective position in input string
			$input_array[$m][$n] = $fn_input[$pos];	 
		}
	}	
	
	/* 	Calculate the next generation of grid based on given conditions 
		Generally there will total 8 cells in neighbour of a particular cell, but
		Any cell at corner of grid will have 3 neighbour cells,
		Any cell at first and last row or first and last column will have 5 neighbour cells
	*/	
	for($i=0; $i<$grid_row; $i++)
	{
		for($j=0; $j<$grid_col; $j++)
		{
			if($i==0)	// when considering cell of first row there will be no neighbour above it. 
			{
				$k_first = 0;
				$k_last = $i+1;
			}
			else if($i==($grid_row-1))	// when considering cell of last row there will be no neighbour below it.
			{
				$k_first = $i-1;
				$k_last = $grid_row-1;
			}
			else
			{
				$k_last = $i+1;
				$k_first = $i-1;
			}
			
			if($j==0)	// when considering cell of first column there will be no neighbour left to it.
			{
				$l_first = 0;
				$l_last = $j+1;
			}
			else if($j==($grid_col-1))	// when considering cell of last column there will be no neighbour right to it.
			{
				$l_first = $j-1;
				$l_last = $grid_col-1;
			}
			else
			{
				$l_last = $j+1;
				$l_first = $j-1;
			}
			$count = countLive($k_first,$k_last,$l_first,$l_last,$input_array);	// call to countLive function
			
			/* modify the grid */
			if($input_array[$i][$j] == '*')
			{
				$count = $count-1;	// Decrement count by 1 as the cell itself is live (*)
				if($count<2 || $count>3)	// condition 1st and 2nd
				{
					$input_array[$i][$j] = '.';
				}
				else						// condition 3rd
				{
					$input_array[$i][$j] = '*';
				}
			}
			
			if($input_array[$i][$j] == '.' && $count == 3)	// condition 4th
			{
				$input_array[$i][$j] = '*';
			}			
		}
	}
	return $input_array;	
}


/***********************************************************************

Function Name: countLive
Input Parameters :
	k_f			-- starting row number of neighbour
	k_l			-- ending row number of neighbour
	l_f			-- starting column number of neighbour
	l_l			-- ending column number of neighbour
	input_array	-- input grid of game_of_life
Return : 
	count 		-- number of live cells(*)
Description :
	This function counts the number of live cells(*) in the neighbourhood of a specific cell

***********************************************************************/

function countLive($k_f,$k_l,$l_f,$l_l,$input_array)
{
	$count = 0;
	for($k=$k_f; $k<=$k_l; $k++)
	{
		for($l=$l_f; $l<=$l_l; $l++)
		{
			if($input_array[$k][$l] == '*')	
			{
				$count++;
			}							
		}
	}
	return $count;
}

/*	Main starts here	*/

$input = "4 8............*......**...........";

$row = substr($input,0,1);	// extract the 0th element as number of row
$col = substr($input,2,1);	// extract the 2nd element as number of column

$input = substr($input,3);	// extract the actual string which starts from 3rd position

$result_array = getNextGeneration($input, $row, $col); // call to getNextGeneration -- get the next generation of game_of_life into result_array


/* store the result array into string using implode */

$tmp_array = array();
foreach ($result_array as $sub_array)	 // transform to 1D
{
	$tmp_array[] = implode('', $sub_array);	
}

$output = implode('', $tmp_array);	// transform to string
$output = $row." ".$col.$output;  	// add no. of row and column at start of output string to get desired output format

echo ($output);	// display the output
?>