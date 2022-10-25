<?php
include_once 'lib/fpdf/fpdf.php';

class PDF_MC_Table extends FPDF
{
    public $widths;
    public $aligns;
    // public function Header()
    // {
       
    //         // Select Arial bold 15
    //         $this->SetFont('Arial','B',15);
    //         // Move to the right
    //         $this->Cell(80);
    //         // Framed title
    //         $this->Cell(30,10,'Title',1,0,'C');
    //         // Line break
    //         $this->Ln(20);
        
        
    // }

    // public function Footer()
    // {
    //                                 $this->SetXY(0,-15);
    //                                 $this->SetFillColor(20, 99, 171);
    //                                 $this->SetTextColor(255,255,255);
    //                                 $this->SetFont('Helvetica', '', 8);
    //                                 $this->Cell(210.00155555556, 5, utf8_decode("LA COTIZACIÓN ES VÁLIDA POR 8 DÍAS."), 0,1, 'C', 1);
    //                                 $this->SetX(0);
    //                                 $this->Cell(210.00155555556, 5, utf8_decode("SI NECESITA MÁS INFORMACIÓN, COMUNÍQUESE CON DANNY QUINTUÑA"), 0,1, 'C', 1);
    //                                 $this->SetX(0);
    //                                 $this->Cell(210.00155555556, 5, utf8_decode("0991410077 / 0999310611"), 0,1, 'C', 1);
    // }

    public function FooterTechComp()
    {
                                    $this->SetXY(0,-15);
                                    $this->SetFillColor(20, 99, 171);
                                    $this->SetTextColor(255,255,255);
                                    $this->SetFont('Helvetica', 'B', 8);
                                    $this->Cell(210.00155555556, 5, utf8_decode("LA COTIZACIÓN ES VÁLIDA POR 8 DÍAS."), 0,1, 'C', 1);
                                    $this->SetX(0);
                                    $this->Cell(210.00155555556, 5, utf8_decode("SI NECESITA MÁS INFORMACIÓN, COMUNÍQUESE CON DANNY QUINTUÑA"), 0,1, 'C', 1);
                                    $this->SetX(0);
                                    $this->Cell(210.00155555556, 5, utf8_decode("0991410077 / 0999310611"), 0,1, 'C', 1);
    }

    public function SetWidths($w)
    {
        //Set the array of column widths
        $this->widths = $w;
    }

    public function SetAligns($a)
    {
        //Set the array of column alignments
        $this->aligns = $a;
    }
    public function RowProducto($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 6;

        // For image height evaluation
        //$h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            if(strlen($rest)<28){
                $this->SetXY($x, ($y+($h/2))-2);
            }else{
                //$this->SetXY($x, $y+3);
                //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                //if(strlen($rest)==83){
                //    $this->SetXY($x, ($y+($h/2))-2);
                //}else{
                    //$this->SetXY($x, $y+(($h/2)-($w/2)));
                //}
                if(strlen($rest)>=135){
                    $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                }else{
                    if(strlen($rest)>=31 && strlen($rest)<=60){
                        $this->SetXY($x, ($y+($h/2))-4);
                    }else{
                        $this->SetXY($x, ($y+($h/2))-6);
                    }
                }
                

            } 
            
            //Print the text
            $this->MultiCell($w, 2, $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(15);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreak($h, 10);
    }
    public function RowProductoBussinesProf($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 6;

        // For image height evaluation
        //$h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            // if(strlen($rest)<28){
            //     $this->SetXY($x, ($y+($h/2))-2);
            // }else{
            //     //$this->SetXY($x, $y+3);
            //     //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //     //if(strlen($rest)==83){
            //     //    $this->SetXY($x, ($y+($h/2))-2);
            //     //}else{
            //         //$this->SetXY($x, $y+(($h/2)-($w/2)));
            //     //}
            //     if(strlen($rest)>=135){
            //         $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //     }else{
            //         if(strlen($rest)>=31 && strlen($rest)<=60){
            //             $this->SetXY($x, ($y+($h/2))-4);
            //         }else{
            //             $this->SetXY($x, ($y+($h/2))-6);
            //         }
            //     }
                

            // } 
            if($this->GetStringWidth($rest)>77){
                $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            }else{
                $this->SetXY($x, ($y+($h/2))-2);
            }
            
            //Print the text
            $this->MultiCell($w, 2, $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(15);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreak($h, 10);
    }
    public function RowProductsCortina($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 3;

        // For image height evaluation
        //$h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            if(strlen($rest)<36){
                $this->SetXY($x, ($y+($h/2))-2);
            }else{
                //$this->SetXY($x, $y+3);
                //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                //if(strlen($rest)==83){
                //    $this->SetXY($x, ($y+($h/2))-2);
                //}else{
                    //$this->SetXY($x, $y+(($h/2)-($w/2)));
                //}
                if(strlen($rest)>=135){
                    $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                }else{
                    if(strlen($rest)>=31 && strlen($rest)<=73){
                        $this->SetXY($x, ($y+($h/2))-4);
                    }else{
                        $this->SetXY($x, ($y+($h/2))-4);
                    }
                }
                

            } 
            $this->SetFont('Arial', '', 6);
            //Print the text
            $this->MultiCell($w, 2, $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(5);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreak($h, 10);
    }
    public function RowCtas($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 8;

        // For image height evaluation
        //$h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            if(strlen($rest)<36){
                $this->SetXY($x, ($y+($h/2))-2);
            }else{
                //$this->SetXY($x, $y+3);
                //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                //if(strlen($rest)==83){
                //    $this->SetXY($x, ($y+($h/2))-2);
                //}else{
                    //$this->SetXY($x, $y+(($h/2)-($w/2)));
                //}
                if(strlen($rest)>=135){
                    $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                }else{
                    if(strlen($rest)>=31 && strlen($rest)<=73){
                        $this->SetXY($x, ($y+($h/2))-4);
                    }else{
                        $this->SetXY($x, ($y+($h/2))-4);
                    }
                }
                

            } 
            $this->SetFont('Arial', '', 7);
            //Print the text
            $this->MultiCell($w, 3, $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(10);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreak($h, 10);
    }
    public function RowProduccion($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 3;

        // For image height evaluation
        //$h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            if(strlen($rest)<40){
                $this->SetXY($x, ($y+($h/2))-2);
            }else{
                //$this->SetXY($x, $y+3);
                //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                //if(strlen($rest)==83){
                //    $this->SetXY($x, ($y+($h/2))-2);
                //}else{
                    //$this->SetXY($x, $y+(($h/2)-($w/2)));
                //}
                if(strlen($rest)>=60){
                    $this->SetXY($x, ($y+($h/2))-2.5);
                }
                

            } 
            $this->SetFont('Arial', '', 7);
            //Print the text
            $this->MultiCell($w, 3, $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(10);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreak($h, 10);
    }
    public function RowFactura_Fisica($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 2;

        // For image height evaluation
        //$h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            //$this->Rect($x, $y, $w, $h);
            
            if(strlen($rest)<120){
                $this->SetXY($x, ($y+($h/2))-2);
            }else{
                //$this->SetXY($x, $y+3);
                //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                //if(strlen($rest)==83){
                //    $this->SetXY($x, ($y+($h/2))-2);
                //}else{
                    //$this->SetXY($x, $y+(($h/2)-($w/2)));
                //}
                if(strlen($rest)>=127.5){
                    $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                }else{
                    if(strlen($rest)>=31 && strlen($rest)<=73){
                        $this->SetXY($x, ($y+($h/2))-4);
                    }else{
                        $this->SetXY($x, ($y+($h/2))-4);
                    }
                }
                

            } 
            $this->SetFont('Arial', '', 10);
            //Print the text
            $this->MultiCell($w, 3, $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(14);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreak($h, 14);
    }
    public function RowcierreCaja($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (3 * $nb) + 3;

        // For image height evaluation
        $h = $h < 24  ? 8 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            $this->SetFont('Arial', '', 7);
            $pos3=strpos($rest,"Cliente");
            
            if($pos3!==false){
                //dd($rest);
                $pos_4=strlen($rest)-7;
                //$a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest3 = substr($rest, 7,$pos_4);
                if($this->GetStringWidth($rest3)>100){
                    $this->SetXY($x, ($y+($h/2))-2);
                }else{
                    $this->SetXY($x, ($y+($h/2))-1.5);
                }
            }else{
                $pos4=strpos($rest,"FormaPago");
                if($pos4!==false){
                    $pos_4=strlen($rest)-7;
                    //$a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                    $rest3 = substr($rest, 9);
                    if($this->GetStringWidth($rest3)>37.5){
                        $this->SetXY($x, $y+(($h/2)-3));
                    }else{
                        $this->SetXY($x, ($y+($h/2))-1.5);
                    }
                }else{
                    $this->SetXY($x, ($y+($h/2))-1.5);
                    $rest3 = $rest;
                }
            }
            // if(strlen($rest)<36){
            //     $this->SetXY($x, ($y+($h/2))-2);
            // }else{
            //     //$this->SetXY($x, $y+3);
            //     //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //     //if(strlen($rest)==83){
            //     //    $this->SetXY($x, ($y+($h/2))-2);
            //     //}else{
            //         //$this->SetXY($x, $y+(($h/2)-($w/2)));
            //     //}
            //     if(strlen($rest)>=135){
            //         $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //     }else{
            //         if(strlen($rest)>=31 && strlen($rest)<=73){
            //             $this->SetXY($x, ($y+($h/2))-4);
            //         }else{
            //             $this->SetXY($x, ($y+($h/2))-4);
            //         }
            //     }
                

            // } 

            $this->SetFont('Arial', '', 7);
            //Print the text
            $this->MultiCell($w, 3, $rest3, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(5);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreak($h, 5);
    }
    public function RowCheckList($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (3 * $nb) + 3;

        // For image height evaluation
        $h = $h < 24  ? 8 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            $this->SetFont('Arial', '', 7);
            $pos3=strpos($rest,"Cliente");
            
            if($pos3!==false){
                //dd($rest);
                $pos_4=strlen($rest)-7;
                //$a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest3 = substr($rest, 7,$pos_4);
                if($this->GetStringWidth($rest3)>100){
                    $this->SetXY($x, ($y+($h/2))-2);
                }else{
                    $this->SetXY($x, ($y+($h/2))-1.5);
                }
            }else{
                $pos4=strpos($rest,"FormaPago");
                if($pos4!==false){
                    $pos_4=strlen($rest)-7;
                    //$a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                    $rest3 = substr($rest, 9);
                    if($this->GetStringWidth($rest3)>37.5){
                        $this->SetXY($x, $y+(($h/2)-3));
                    }else{
                        $this->SetXY($x, ($y+($h/2))-1.5);
                    }
                }else{
                    $this->SetXY($x, ($y+($h/2))-1.5);
                    $rest3 = $rest;
                }
            }
            // if(strlen($rest)<36){
            //     $this->SetXY($x, ($y+($h/2))-2);
            // }else{
            //     //$this->SetXY($x, $y+3);
            //     //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //     //if(strlen($rest)==83){
            //     //    $this->SetXY($x, ($y+($h/2))-2);
            //     //}else{
            //         //$this->SetXY($x, $y+(($h/2)-($w/2)));
            //     //}
            //     if(strlen($rest)>=135){
            //         $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //     }else{
            //         if(strlen($rest)>=31 && strlen($rest)<=73){
            //             $this->SetXY($x, ($y+($h/2))-4);
            //         }else{
            //             $this->SetXY($x, ($y+($h/2))-4);
            //         }
            //     }
                

            // } 

            $this->SetFont('Arial', '', 7);
            //Print the text
            $this->MultiCell($w, 3, $rest3, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(6);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreak($h, 6);
    }
    public function RowAts($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 1;

        // For image height evaluation
        //$h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetFillColor(174, 176, 208);
            //Draw the border
            $this->Rect($x, $y, $w, $h,'F');
            
            // if(strlen($rest)<36){
            //     $this->SetXY($x, ($y+($h/2))-2);
            // }else{
            //     //$this->SetXY($x, $y+3);
            //     //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //     //if(strlen($rest)==83){
            //     //    $this->SetXY($x, ($y+($h/2))-2);
            //     //}else{
            //         //$this->SetXY($x, $y+(($h/2)-($w/2)));
            //     //}
            //     if(strlen($rest)>=135){
            //         $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //     }else{
            //         if(strlen($rest)>=31 && strlen($rest)<=73){
            //             $this->SetXY($x, ($y+($h/2))-4);
            //         }else{
            //             $this->SetXY($x, ($y+($h/2))-4);
            //         }
            //     }
                

            // } 
            $this->SetFont('Arial', '', 7);
            if($this->GetStringWidth($rest)<=80.14){
                $this->SetXY($x, $y+($h/2)-1);
            }else{
                $this->SetXY($x, $y+(($h/2)*($w/$this->GetStringWidth($rest))-2));
            }
            

            //Print the text
            //$this->SetFont('Arial', '', 8);
            //$this->SetFillColor(174, 176, 208);
            $this->SetFont('Arial', '', 7);
            //Print the text
            $this->MultiCell($w, 2.5, $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(10);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreakATS($h, 10);

    }
    public function RowNTCFactura($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 3;

        // For image height evaluation
        //$h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetFillColor(174, 176, 208);
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            // if(strlen($rest)<36){
            //     $this->SetXY($x, ($y+($h/2))-2);
            // }else{
            //     //$this->SetXY($x, $y+3);
            //     //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //     //if(strlen($rest)==83){
            //     //    $this->SetXY($x, ($y+($h/2))-2);
            //     //}else{
            //         //$this->SetXY($x, $y+(($h/2)-($w/2)));
            //     //}
            //     if(strlen($rest)>=135){
            //         $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //     }else{
            //         if(strlen($rest)>=31 && strlen($rest)<=73){
            //             $this->SetXY($x, ($y+($h/2))-4);
            //         }else{
            //             $this->SetXY($x, ($y+($h/2))-4);
            //         }
            //     }
                

            // } 
            $this->SetFont('Helvetica', '', 8);
            if($this->GetStringWidth($rest)<=86){
                $this->SetXY($x, $y+($h/2)-1);
            }else{
                $this->SetXY($x, $y+(($h/2)*($w/$this->GetStringWidth($rest))-2));
            }
            

            //Print the text
            //$this->SetFont('Arial', '', 8);
            //$this->SetFillColor(174, 176, 208);
            $this->SetFont('Helvetica', '', 8);
            //Print the text
            $this->MultiCell($w, 2.5, $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(10);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreakATS($h, 10);

    }
    public function RowProducts($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 7;

        // For image height evaluation
        $h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            if(strlen($rest)<28){
                $this->SetXY($x, ($y+($h/2))-2);
            }else{
                //$this->SetXY($x, $y+3);
                //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                //if(strlen($rest)==83){
                //    $this->SetXY($x, ($y+($h/2))-2);
                //}else{
                    //$this->SetXY($x, $y+(($h/2)-($w/2)));
                //}
                if(strlen($rest)>=100){
                    $this->SetXY($x, $y+1);
                }else{
                    if(strlen($rest)>=31 && strlen($rest)<=60){
                        $this->SetXY($x, ($y+($h/2))-4);
                    }else{
                        $this->SetXY($x, ($y+($h/2))-6);
                    }
                }
                

            } 
            
            
            $this->SetFont('Arial', '', 5);
            $this->MultiCell($w, 4 , $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(10);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreak($h, 10);
    }
    public function RowProductsInventario($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 4;

        // For image height evaluation
        //$h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            // if(strlen($rest)<28){
            //     $this->SetXY($x, ($y+($h/2))-2);
            // }else{
            //     //$this->SetXY($x, $y+3);
            //     //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //     //if(strlen($rest)==83){
            //     //    $this->SetXY($x, ($y+($h/2))-2);
            //     //}else{
            //         //$this->SetXY($x, $y+(($h/2)-($w/2)));
            //     //}
            //     if(strlen($rest)>=100){
            //         $this->SetXY($x, $y+1);
            //     }else{
            //         if(strlen($rest)>=31 && strlen($rest)<=60){
            //             $this->SetXY($x, ($y+($h/2))-4);
            //         }else{
            //             $this->SetXY($x, ($y+($h/2))-6);
            //         }
            //     }
                

            // } 
            if($this->GetStringWidth($rest)<=57.5){
                $this->SetXY($x, $y+($h/2)-2);
            }else{
                $this->SetXY($x, $y+(($h/2)*($w/$this->GetStringWidth($rest))-0.5));
            }
            
            
            $this->SetFont('Arial', '', 7);
            $this->MultiCell($w, 2 , $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(10);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreak($h, 10);
    }
    public function RowProductsGeneral($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (3 * $nb) + 10;

        // For image height evaluation
        $h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            if(strlen($rest)<28){
                $this->SetXY($x, ($y+($h/2))-2);
            }else{
                //$this->SetXY($x, $y+3);
                //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                //if(strlen($rest)==83){
                //    $this->SetXY($x, ($y+($h/2))-2);
                //}else{
                    //$this->SetXY($x, $y+(($h/2)-($w/2)));
                //}
                if(strlen($rest)>=100){
                    $this->SetXY($x, $y+1);
                }else{
                    if(strlen($rest)>=31 && strlen($rest)<=60){
                        $this->SetXY($x, ($y+($h/2))-4);
                    }else{
                        $this->SetXY($x, ($y+($h/2))-6);
                    }
                }
                

            } 
            
            
            $this->SetFont('Arial', '', 5);
            $this->MultiCell($w, 3 , $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(5);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreak($h, 5);
    }
    public function RowProductsGeneralImport($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 4;

        // For image height evaluation
        $h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            // if(strlen($rest)<28){
            //     $this->SetXY($x, ($y+($h/2))-2);
            // }else{
            //     //$this->SetXY($x, $y+3);
            //     //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //     //if(strlen($rest)==83){
            //     //    $this->SetXY($x, ($y+($h/2))-2);
            //     //}else{
            //         //$this->SetXY($x, $y+(($h/2)-($w/2)));
            //     //}
            //     if(strlen($rest)>=135){
            //         if(strlen($rest)>=250){
            //             $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))));
            //         }else{
            //             $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //         }
                    
            //     }else{
            //         if(strlen($rest)>=31 && strlen($rest)<=60){
            //             $this->SetXY($x, ($y+($h/2))-4);
            //         }else{

            //             $this->SetXY($x, ($y+($h/2))-6);
            //         }
            //     }
                

            // } 
            $this->SetFont('Arial', '', 5);
            if($this->GetStringWidth($rest)<=114){
                $this->SetXY($x, $y+($h/2)-1);
            }else{
                $this->SetXY($x, $y+(($h/2)*($w/$this->GetStringWidth($rest))-0.5));
            }
            

            //Print the text
            $this->SetFont('Arial', '', 5);
            
            //Print the text
            $this->MultiCell($w, 2, $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);

            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(5);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreakProform($h, 5);
    }
    public function RowProductsGroy($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 10;

        // For image height evaluation
        $h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            // if(strlen($rest)<28){
            //     $this->SetXY($x, ($y+($h/2))-2);
            // }else{
            //     //$this->SetXY($x, $y+3);
            //     //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //     //if(strlen($rest)==83){
            //     //    $this->SetXY($x, ($y+($h/2))-2);
            //     //}else{
            //         //$this->SetXY($x, $y+(($h/2)-($w/2)));
            //     //}
            //     if(strlen($rest)>=100){
            //         $this->SetXY($x, $y+1);
            //     }else{
            //         if(strlen($rest)>=31 && strlen($rest)<=60){
            //             $this->SetXY($x, ($y+($h/2))-4);
            //         }else{
            //             $this->SetXY($x, ($y+($h/2))-6);
            //         }
            //     }
                

            // } 
            
            
            // $this->SetFont('Arial', '', 5);
            $this->SetFont('Arial', '', 8);
            if($this->GetStringWidth($rest)<=119){
                $this->SetXY($x, $y+($h/2)-1);
            }else{
                $this->SetXY($x, $y+(($h/2)*($w/$this->GetStringWidth($rest))-0.5));
            }
            

            //Print the text
            $this->SetFont('Arial', '', 8);
            $this->MultiCell($w, 3 , $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(5);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreak($h, 5);
    }
    public function RowProductsELETE($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 6;

        // For image height evaluation
        $h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            if(strlen($rest)<28){
                $this->SetXY($x, ($y+($h/2))-2);
            }else{
                //$this->SetXY($x, $y+3);
                //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                //if(strlen($rest)==83){
                //    $this->SetXY($x, ($y+($h/2))-2);
                //}else{
                    //$this->SetXY($x, $y+(($h/2)-($w/2)));
                //}
                if(strlen($rest)>=100){
                    $this->SetXY($x, $y+1);
                }else{
                    if(strlen($rest)>=31 && strlen($rest)<=60){
                        $this->SetXY($x, ($y+($h/2))-4);
                    }else{
                        $this->SetXY($x, ($y+($h/2))-4.5);
                    }
                }
                

            } 
            $mystring2=$data[$i];
            $pos2=strpos($mystring2,"Centrado");
            if($pos2 !== false){
                $this->SetFont('Arial', '', 8);
            }else{
                $pos_der2=strpos($mystring2,"Derecha");
                if($pos_der2 !== false){
                    $this->SetFont('Arial', '', 8);
                }else{
                    $this->SetFont('Arial', '', 6);
                }
            }

            
            $this->MultiCell($w, 4 , $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(10);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreak($h, 10);
    }
    public function RowProductsELETEIMport($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 6;

        // For image height evaluation
        $h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            if(strlen($rest)<28){
                $this->SetXY($x, ($y+($h/2))-2);
            }else{
                //$this->SetXY($x, $y+3);
                //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                //if(strlen($rest)==83){
                //    $this->SetXY($x, ($y+($h/2))-2);
                //}else{
                    //$this->SetXY($x, $y+(($h/2)-($w/2)));
                //}
                if(strlen($rest)>=100){
                    $this->SetXY($x, $y+1);
                }else{
                    if(strlen($rest)>=31 && strlen($rest)<=60){
                        $this->SetXY($x, ($y+($h/2))-4);
                    }else{
                        $this->SetXY($x, ($y+($h/2))-4.5);
                    }
                }
                

            } 
            $mystring2=$data[$i];
            $pos2=strpos($mystring2,"Centrado");
            if($pos2 !== false){
                $this->SetFont('Arial', '', 8);
            }else{
                $pos_der2=strpos($mystring2,"Derecha");
                if($pos_der2 !== false){
                    $this->SetFont('Arial', '', 8);
                }else{
                    $this->SetFont('Arial', '', 6);
                }
            }

            
            $this->MultiCell($w, 4 , $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(5);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreak($h, 5);
    }
    public function Row($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 4;

        // For image height evaluation
        $h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            if(strlen($rest)<28){
                $this->SetXY($x, ($y+($h/2))-2);
            }else{
                //$this->SetXY($x, $y+3);
                //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                //if(strlen($rest)==83){
                //    $this->SetXY($x, ($y+($h/2))-2);
                //}else{
                    //$this->SetXY($x, $y+(($h/2)-($w/2)));
                //}
                if(strlen($rest)>=135){
                    if(strlen($rest)>=250){
                        $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))));
                    }else{
                        $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                    }
                    
                }else{
                    if(strlen($rest)>=31 && strlen($rest)<=60){
                        $this->SetXY($x, ($y+($h/2))-4);
                    }else{

                        $this->SetXY($x, ($y+($h/2))-6);
                    }
                }
                

            } 
            
            //Print the text
            $this->MultiCell($w, 2, $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);

            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(5);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreakProform($h, 5);
    }
    public function RowOrdenGrpoSolis($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 4;

        // For image height evaluation
        $h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            // if(strlen($rest)<28){
            //     $this->SetXY($x, ($y+($h/2))-2);
            // }else{
            //     //$this->SetXY($x, $y+3);
            //     //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //     //if(strlen($rest)==83){
            //     //    $this->SetXY($x, ($y+($h/2))-2);
            //     //}else{
            //         //$this->SetXY($x, $y+(($h/2)-($w/2)));
            //     //}
            //     if(strlen($rest)>=135){
            //         if(strlen($rest)>=250){
            //             $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))));
            //         }else{
            //             $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //         }
                    
            //     }else{
            //         if(strlen($rest)>=31 && strlen($rest)<=60){
            //             $this->SetXY($x, ($y+($h/2))-4);
            //         }else{

            //             $this->SetXY($x, ($y+($h/2))-6);
            //         }
            //     }
                

            // } 
            $this->SetFont('Helvetica', '', 4);
            if($this->GetStringWidth($rest)<=114){
                $this->SetXY($x, $y+($h/2)-1);
            }else{
                $this->SetXY($x, $y+(($h/2)*($w/$this->GetStringWidth($rest))-0.5));
            }
            

            //Print the text
            $this->SetFont('Helvetica', '', 4);
            
            //Print the text
            $this->MultiCell($w, 2, $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);

            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(5);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreakProform($h, 5);
    }
    public function RowDataMegaRedes($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 4;

        // For image height evaluation
        $h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            // if(strlen($rest)<28){
            //     $this->SetXY($x, ($y+($h/2))-2);
            // }else{
            //     //$this->SetXY($x, $y+3);
            //     //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //     //if(strlen($rest)==83){
            //     //    $this->SetXY($x, ($y+($h/2))-2);
            //     //}else{
            //         //$this->SetXY($x, $y+(($h/2)-($w/2)));
            //     //}
            //     if(strlen($rest)>=135){
            //         if(strlen($rest)>=250){
            //             $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))));
            //         }else{
            //             $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //         }
                    
            //     }else{
            //         if(strlen($rest)>=31 && strlen($rest)<=60){
            //             $this->SetXY($x, ($y+($h/2))-4);
            //         }else{

            //             $this->SetXY($x, ($y+($h/2))-6);
            //         }
            //     }
                

            // } 
            
            $this->SetFont('Arial', '', 8);
            if($this->GetStringWidth($rest)<=119){
                $this->SetXY($x, $y+($h/2)-1);
            }else{
                $this->SetXY($x, $y+(($h/2)*($w/$this->GetStringWidth($rest))-0.5));
            }
            

            //Print the text
            $this->SetFont('Arial', '', 8);
            $this->MultiCell($w, 3, $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);

            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(7);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreakProform($h, 7);
    }
    public function RowDataTechcomp($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 7;

        // For image height evaluation
        //$h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 10 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            // if(strlen($rest)<28){
            //     $this->SetXY($x, ($y+($h/2))-2);
            // }else{
            //     //$this->SetXY($x, $y+3);
            //     //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //     //if(strlen($rest)==83){
            //     //    $this->SetXY($x, ($y+($h/2))-2);
            //     //}else{
            //         //$this->SetXY($x, $y+(($h/2)-($w/2)));
            //     //}
            //     if(strlen($rest)>=135){
            //         if(strlen($rest)>=250){
            //             $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))));
            //         }else{
            //             $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
            //         }
                    
            //     }else{
            //         if(strlen($rest)>=31 && strlen($rest)<=60){
            //             $this->SetXY($x, ($y+($h/2))-4);
            //         }else{

            //             $this->SetXY($x, ($y+($h/2))-6);
            //         }
            //     }
                

            // } 
            
            $this->SetFont('Helvetica', '', 8);
            if($this->GetStringWidth($rest)<=111){
                $this->SetXY($x, $y+($h/2)-1);
            }else{
                $this->SetXY($x, $y+(($h/2)*($w/$this->GetStringWidth($rest))-0.5));
            }
            

            //Print the text
            $this->SetFont('Helvetica', '', 8);
            $this->MultiCell($w, 3, $rest, 'RL', $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);

            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(7);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreakProform($h, 7);
    }
    public function RowDataCompanyMs($data, $margin){
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 7;

        // For image height evaluation
        $h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            if(strlen($rest)<64){
                $this->SetXY($x, ($y+($h/2))-1);
            }else{
                //$this->SetXY($x, $y+3);
                //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                //if(strlen($rest)==83){
                //    $this->SetXY($x, ($y+($h/2))-2);
                //}else{
                    //$this->SetXY($x, $y+(($h/2)-($w/2)));
                //}
                if(strlen($rest)>=135){
                    $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                }else{
                    if(strlen($rest)>=31 && strlen($rest)<=60){
                        $this->SetXY($x, ($y+($h/2))-4);
                    }else{
                        $this->SetXY($x, ($y+($h/2))-5);
                    }
                }
                

            } 
            
            //Print the text
            $this->MultiCell($w, 2, $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(5);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreak($h, 5);
    }
    public function HeadWithoutImage($data, $margin, $aligns = 'L', $height_row = 2)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + $height_row;

        //Draw the cells of the row
        $this->SetX($margin);
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($aligns) ? $aligns : 'C';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetXY($x, $y + 3);
            //Print the text
            $this->MultiCell($w, 3, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
        $this->SetX($margin);
        //Issue a page break first if needed
        $this->CheckPageBreak($h, $margin);
    }


    /**
     * Draw the cells without image
     */
    public function RowWithoutImage($data, $margin, $aligns = 'C', $height_row = 6)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + $height_row;

        //Draw the cells of the row
        $this->SetX($margin);
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($aligns) ? $aligns : 'C';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetXY($x, $y + 3);
            //Print the text
            $this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
        $this->SetX($margin);
        //Issue a page break first if needed
        $this->CheckPageBreak($h, $margin);
    }
    public function RowWithoutImageBodega($data, $margin, $aligns = 'C', $height_row = 1)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + $height_row;

        //Draw the cells of the row
        $this->SetX($margin);
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    
                    
                }
            }
            //$a = isset($aligns) ? $aligns : 'C';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetXY($x, $y+0.5);
            //Print the text
            $this->MultiCell($w, 2, $rest, 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
        $this->SetX($margin);
        //Issue a page break first if needed
        $this->CheckPageBreak($h, $margin);
    }
    public function RowWithoutImageATS($data, $margin, $aligns = 'C', $height_row = 1)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + $height_row;

        //Draw the cells of the row
        $this->SetX($margin);
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    
                    
                }
            }
            //$a = isset($aligns) ? $aligns : 'C';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetFont('Helvetica', '', 6);
            // if($this->GetStringWidth($rest)<=75.95){
            //     $this->SetXY($x, $y+0.5);
            // }else{
            //     $this->SetXY($x, $y*(+$this->GetStringWidth($rest)));
            // }
            $this->SetXY($x, $y+($h/3));
            
            
            //Print the text
            
            $this->MultiCell($w, 2, $rest, 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
        $this->SetX($margin);
        //Issue a page break first if needed
        $this->CheckPageBreak($h, $margin);
    }
    public function RowFooter($data, $margin, $aligns = 'C', $height_row = 2)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + $height_row;

        //Draw the cells of the row
        $this->SetX($margin);
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($aligns) ? $aligns : 'C';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetXY($x, $y + 3);
            //Print the text
            $this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
        $this->SetX($margin);
        //Issue a page break first if needed
        $this->CheckPageBreak($h, $margin);
    }
    public function RowData($data, $margin, $aligns = 'C', $height_row = 6)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + $height_row;

        //Draw the cells of the row
        $this->SetX($margin);
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,".");
            if($pos===false){
                $a = isset($aligns) ? 'C' : 'C';
            }else{
                $a = isset($aligns) ? 'R' : 'R';
            }
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetXY($x, $y + 3);
            //Print the text
            $this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
        $this->SetX($margin);
        //Issue a page break first if needed
        $this->CheckPageBreak($h, $margin);
    }
    /**
     * Draw the cells without image but with border
     */
    public function RowWithoutImageWithBorder($data, $margin, $aligns = 'C', $border = 'TB', $height_row = 6)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + $height_row;

        $h = $h > 8 ? $h + 8 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($aligns) ? $aligns : 'C';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetXY($x, $y + 3);
            //Print the text
            $this->MultiCell($w, 6, $data[$i], $border, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
        $this->SetX($margin);
        //Issue a page break first if needed
        $this->CheckPageBreak($h, $margin);
    }
    public function RowWithoutImageWithBorderData($data, $margin, $aligns = 'R', $border = 'TB', $height_row = 6)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + $height_row;

        $h = $h > 8 ? $h + 8 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($aligns) ? $aligns : 'R';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetXY($x, $y + 3);
            //Print the text
            $this->MultiCell($w, 6, $data[$i], $border, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
            
        }
        //Go to the next line
        $this->Ln($h);
        $this->SetX($margin);
        //Issue a page break first if needed
        $this->CheckPageBreak($h, $margin);
    }

    public function CheckPageBreak($h, $margin)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h >= $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
            $this->SetXY($margin, 15);
        }

    }
    public function CheckPageBreakProform($h, $margin)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h >= $this->PageBreakTrigger) {
            
            //$this->SetAutoPageBreak(true,$this->GetPageHeight()-$this->GetY());
            $this->AddPage($this->CurOrientation);
            
            $this->SetXY($margin, 15);
        }

    }
    public function CheckPageBreakATS($h, $margin)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h >= $this->PageBreakTrigger) {
            
            //$this->SetAutoPageBreak(true,2);
            $this->AddPage($this->CurOrientation);
            
            $this->SetXY($margin, 5);
        }

    }

    public function NbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }

        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n") {
            $nb--;
        }

        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
            }

            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }

                } else {
                    $i = $sep + 1;
                }

                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }

        }
        return $nl;
    }
}
class Header_Ctas_Cobrar_Dias_Venc extends FPDF
{
    public $widths;
    public $aligns;
    public function Header()
    {
       if($this->PageNo()>1){
            // Select Arial bold 15
            $this->SetFont('Arial','B',8);
            // Move to the right
            $this->SetXY(10,$this->GetY()-1);
            // Framed title
            $this->Cell(25,6,'No. Doc',1,0,'C');
            $this->Cell(70,6,'Cliente',1,0,'C');
            $this->Cell(15,6,'Fecha',1,0,'C');
            $this->Cell(20,6,'Valor',1,0,'C');
            $this->Cell(15,6,'-120',1,0,'C');
            $this->Cell(15,6,'-90',1,0,'C');
            $this->Cell(15,6,'-60',1,0,'C');
            $this->Cell(15,6,'-30',1,0,'C');
            $this->Cell(25,6,'Total Vencido',1,0,'C');
            $this->Cell(60,6,'Observaciones',1,0,'C');
            // Line break
            $this->Ln(20);
       }
        
    }

    // public function Footer()
    // {
    //                                 $this->SetXY(0,-15);
    //                                 $this->SetFillColor(20, 99, 171);
    //                                 $this->SetTextColor(255,255,255);
    //                                 $this->SetFont('Helvetica', '', 8);
    //                                 $this->Cell(210.00155555556, 5, utf8_decode("LA COTIZACIÓN ES VÁLIDA POR 8 DÍAS."), 0,1, 'C', 1);
    //                                 $this->SetX(0);
    //                                 $this->Cell(210.00155555556, 5, utf8_decode("SI NECESITA MÁS INFORMACIÓN, COMUNÍQUESE CON DANNY QUINTUÑA"), 0,1, 'C', 1);
    //                                 $this->SetX(0);
    //                                 $this->Cell(210.00155555556, 5, utf8_decode("0991410077 / 0999310611"), 0,1, 'C', 1);
    // }



    public function SetWidths($w)
    {
        //Set the array of column widths
        $this->widths = $w;
    }

    public function SetAligns($a)
    {
        //Set the array of column alignments
        $this->aligns = $a;
    }
    public function RowCtas($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 8;

        // For image height evaluation
        //$h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? 25 : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $mystring=$data[$i];
            $pos=strpos($mystring,"Centrado");
            
            if($pos !== false){
                $pos_2=strlen($mystring)-8;
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                $rest = substr($mystring, 0, $pos_2);
            }else{
                $pos_der=strpos($mystring,"Derecha");
                if($pos_der !== false){
                    $pos_2=strlen($mystring)-7;
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
                    $rest = substr($mystring, 0, $pos_2);
                }else{
                    if($mystring=="INMEDIATA"){
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
                        $rest = $mystring;
                    }else{
                        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                        $rest = $mystring;
                    }
                    
                }
            }
            
            
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            
            if(strlen($rest)<36){
                $this->SetXY($x, ($y+($h/2))-2);
            }else{
                //$this->SetXY($x, $y+3);
                //$this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                //if(strlen($rest)==83){
                //    $this->SetXY($x, ($y+($h/2))-2);
                //}else{
                    //$this->SetXY($x, $y+(($h/2)-($w/2)));
                //}
                if(strlen($rest)>=135){
                    $this->SetXY($x, $y+(($h/2)*($w/strlen($rest))+3));
                }else{
                    if(strlen($rest)>=31 && strlen($rest)<=73){
                        $this->SetXY($x, ($y+($h/2))-4);
                    }else{
                        $this->SetXY($x, ($y+($h/2))-4);
                    }
                }
                

            } 
            $this->SetFont('Arial', '', 7);
            //Print the text
            $this->MultiCell($w, 3, $rest, 0, $a);
            //$this->MultiCell($w, 2, $data[$i], 0, $a);
            //Put the position to the right of the cell

            $this->SetXY($x + $w, $y);
            
            
        }
        //Go to the next line
        
        $this->Ln($h);
        $this->SetX(10);
        
        
        //Issue a page break first if needed
        $this->CheckPageBreak($h, 10);
    }


    public function CheckPageBreak($h, $margin)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h >= $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
            $this->SetXY($margin, 15);
        }

    }

    public function NbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }

        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n") {
            $nb--;
        }

        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
            }

            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }

                } else {
                    $i = $sep + 1;
                }

                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }

        }
        return $nl;
    }
}
