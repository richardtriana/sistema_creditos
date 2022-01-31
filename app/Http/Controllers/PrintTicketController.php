<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Credit;
use App\Models\Installment;
use App\Models\Headquarter;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;


class PrintTicketController extends Controller
{
    public function printInstallment($id)
    {
        // Orden
        $headquarter = Headquarter::first();

        if (!$headquarter->pos_printer || $headquarter->pos_printer == '') {
            return false;
        }
        $installment = Installment::findOrFail($id);
        $credit = $installment->credit;
        $client = $credit['client'];

        // Config de impresora
        $connector = new WindowsPrintConnector($headquarter->pos_printer);

        try {

            $printer = new Printer($connector);
            $printer->initialize();
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            try {
                $logo = EscposImage::load('logo.jpg', false);
                $printer->bitImage($logo);
            } catch (Exception $e) {
                /* Images not supported on your PHP, or image file not found */
                //$printer->text($e->getMessage() . "\n");
            }

            $printer->setTextSize(1, 2);
            $printer->setEmphasis(true);
            $printer->text($headquarter->headquarter . "\n");
            $printer->setTextSize(1, 1);
            $printer->setEmphasis(false);
            $printer->text("NIT: ");
            $printer->text($headquarter->nit . "\n");
            $printer->text("Dirección: ");
            $printer->text($headquarter->address . "\n");

            $printer->setEmphasis(true);
            $printer->setTextSize(1, 2);
            $printer->text("\n");
            $printer->text("Reporte de pago");
            $printer->text("\n");
            $printer->text("\n");
            $printer->setTextSize(1, 1);
            // $printer->text("Cajero(a): ");
            // $printer->text($system_user->name . "\n");
            $printer->setEmphasis(false);

            $printer->text(sprintf('%-20s %-20s', 'Client', $client->name . ' ' . $client->last_name));
            $printer->text("\n");
            $printer->text(sprintf('%-20s %-20s', 'Fecha', date('Y-m-d h:i:s A')));
            $printer->text("\n");
            $printer->text(sprintf('%-20s %-20s', 'Crédito', $credit->credit_value));
            $printer->text("\n");
            $printer->text(sprintf('%-20s %-20s', 'Nro Operacion', $installment->id));
            $printer->text("\n");
            $printer->text(sprintf('%-20s %-20s', 'Fecha Installment', $installment->payment_date));
            $printer->text("\n");
            $printer->text(sprintf('%-20s %-20s', 'Monto Cancelado', $installment->value));
            $printer->text("\n");
            $printer->text("\n");
            $printer->setLineSpacing(2);
            $printer->setEmphasis(false);
            $printer->setFont(Printer::MODE_FONT_B);
            $printer->text("Tecnoplus");
            $printer->text("\nwww.tecnoplus.com\n");
            $printer->cut();
            $printer->pulse();
            $printer->close();

            return redirect()->back()->with("mensaje", "Ticket impreso");
        } catch (\Throwable $th) {
            return 'NO se pudo imprimri';
        }
    }

    public function printPayment($id, $amount = null, $no_installment = null)
    {
        $company = Company::first();
        // Orden
        $headquarter = Headquarter::first();

        if (!$headquarter->pos_printer || $headquarter->pos_printer == '') {
            return false;
        }

        $credit = Credit::findOrFail($id);
        $client = $credit['client'];

        // Config de impresora
        $connector = new WindowsPrintConnector($headquarter->pos_printer);

        try {

            $printer = new Printer($connector);
            $printer->initialize();
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            try {
                $logo = EscposImage::load('logo.jpg', false);
                $printer->bitImage($logo);
            } catch (Exception $e) {
                /* Images not supported on your PHP, or image file not found */
                //$printer->text($e->getMessage() . "\n");
            }

            $printer->setTextSize(1, 2);
            $printer->setEmphasis(true);
            $printer->text($company->name . "\n");
            $printer->setEmphasis(true);
            $printer->text($headquarter->headquarter . "\n");
            $printer->setTextSize(1, 1);
            $printer->setEmphasis(false);
            $printer->text("NIT: ");
            $printer->text($headquarter->nit . "\n");
            $printer->text("Dirección: ");
            $printer->text($headquarter->address . "\n");

            $printer->setEmphasis(true);
            $printer->setTextSize(1, 2);
            $printer->text("\n");
            $printer->text("Reporte de pago");
            $printer->text("\n");
            $printer->text("\n");
            $printer->setTextSize(1, 1);
            // $printer->text("Cajero(a): ");
            // $printer->text($system_user->name . "\n");
            $printer->setEmphasis(false);

            $printer->text(sprintf('%-20s %-20s', 'Client', $client->name . ' ' . $client->last_name));
            $printer->text("\n");
            $printer->text(sprintf('%-20s %-20s', 'Fecha', date('Y-m-d h:i:s A')));
            $printer->text("\n");
            $printer->text(sprintf('%-20s %-20s', 'Crédito', $credit->credit_value));
            // $printer->text("\n");
            $printer->text(sprintf('%-20s %-20s', 'Nro cuota', $no_installment));
            $printer->text("\n");
            $printer->text(sprintf('%-20s %-20s', 'Monto Cancelado', $amount));
            $printer->text("\n");
            $printer->text("\n");
            $printer->setLineSpacing(2);
            $printer->setEmphasis(false);
            $printer->setFont(Printer::MODE_FONT_B);
            $printer->text("Tecnoplus");
            $printer->text("\nwww.tecnoplus.com\n");
            $printer->cut();
            $printer->pulse();
            $printer->close();

            return redirect()->back()->with("mensaje", "Ticket impreso");
        } catch (\Throwable $th) {
            return 'NO se pudo imprimri';
        }
    }
}
