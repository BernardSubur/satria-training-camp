<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Paket;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_laporan_transaksi()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $paket = Paket::factory()->create(['harga' => 300000]);
        Payment::factory()->create([
            'paket_id' => $paket->id,
            'status' => 'success'
        ]);
        Payment::factory()->create([
            'paket_id' => $paket->id,
            'status' => 'pending'
        ]); // Should not be counted in sum

        $response = $this->actingAs($admin)->get('/admin/laporan-transaksi');

        $response->assertStatus(200);
        $response->assertSee('300.000'); // Total transaksi from 1 success payment
    }

    public function test_admin_can_export_pdf_laporan_transaksi()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        \Barryvdh\DomPDF\Facade\Pdf::shouldReceive('loadView')
            ->once()
            ->andReturnSelf();
            
        \Barryvdh\DomPDF\Facade\Pdf::shouldReceive('download')
            ->once()
            ->with('laporan-transaksi.pdf')
            ->andReturn(response('fake pdf content', 200, ['content-type' => 'application/pdf']));

        $response = $this->actingAs($admin)->get('/admin/export-pdf');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_non_admin_cannot_view_laporan()
    {
        $member = User::factory()->create(['role' => 'member']);
        $response = $this->actingAs($member)->get('/admin/laporan-transaksi');

        $response->assertStatus(302);
    }
}
