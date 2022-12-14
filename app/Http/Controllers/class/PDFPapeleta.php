<?php
include_once 'lib/fpdf/fpdf.php';

/**
 * This Class is only for PAPELETAS DE ROL DE PAGOS
 */
class PDF_Papeleta extends FPDF
{
    public $widths;
    public $aligns;

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

    public function Row($data, $margin)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 6;

        // For image height evaluation
        $h = $h < 24 && $data[0] !== utf8_decode('* sin imagen') ? ($h + 25) - ($h / 2) : $h;

        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
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
    public function RowWithoutImage($data, $margin, $aligns = 'C')
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 2;

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
    public function RowWithoutImageWithBorder($data, $margin, $aligns = 'C', $border = 'TB')
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }

        $h = (2 * $nb) + 6;

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
