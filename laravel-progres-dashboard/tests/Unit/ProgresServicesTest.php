<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\Progres\DataCleanerService;

class ProgresServicesTest extends TestCase
{
    protected function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }

    public function test_clean_data()
    {
        $service = new DataCleanerService();
        $row = [
            'pagu' => 'Rp 1.500.000,50',
            'realisasi' => '-',
            'lokasi' => 'nan',
            'keuangan_percent' => '75.5%',
        ];

        $cleaned = $this->invokeMethod($service, 'cleanData', [$row]);

        $this->assertEquals(1500000.50, $cleaned['pagu']);
        $this->assertNull($cleaned['realisasi']);
        $this->assertNull($cleaned['lokasi']);
        $this->assertEquals(75.5, $cleaned['keuangan_percent']);
    }

    public function test_paket_detail_detector()
    {
        $service = new DataCleanerService();
        
        $validPaket = ['kode' => '123', 'paket' => 'Pembangunan A', 'lokasi' => 'Jabar'];
        $this->assertTrue($this->invokeMethod($service, 'isPaketDetail', [$validPaket]));

        $invalidPaket = ['kode' => '123', 'paket' => 'TOTAL KESELURUHAN', 'lokasi' => 'Jabar'];
        $this->assertFalse($this->invokeMethod($service, 'isPaketDetail', [$invalidPaket]));

        $missingLokasi = ['kode' => '123', 'paket' => 'Paket A', 'lokasi' => null];
        $this->assertFalse($this->invokeMethod($service, 'isPaketDetail', [$missingLokasi]));
    }

    public function test_metric_calculator()
    {
        $service = new DataCleanerService();
        $row = [
            'pagu' => 1000,
            'pagu_setelah_efisiensi' => 900,
            'realisasi' => 450,
            'keuangan_setelah_efisiensi_percent' => 50,
            'fisik_setelah_efisiensi_percent' => 60,
        ];

        $result = $this->invokeMethod($service, 'calculateMetrics', [$row]);

        $this->assertEquals(450, $result['sisa_anggaran']); // 900 - 450
        $this->assertEquals(45, $result['serapan_terhadap_pagu']); // 450/1000 * 100
        $this->assertEquals(50, $result['serapan_terhadap_pagu_efisiensi']); // 450/900 * 100
        $this->assertEquals(10, $result['gap_fisik_keuangan']); // 60 - 50
    }

    public function test_risk_scoring()
    {
        $service = new DataCleanerService();
        
        $kritis = [
            'realisasi' => 0,
            'keuangan_setelah_efisiensi_percent' => 0,
            'fisik_setelah_efisiensi_percent' => 0,
            'gap_fisik_keuangan' => 0,
        ];
        $this->assertEquals('Kritis', $this->invokeMethod($service, 'calculateRisk', [$kritis])['status_risiko']);
        $this->assertEquals(3, $this->invokeMethod($service, 'calculateRisk', [$kritis])['risk_score']);

        $aman = [
            'realisasi' => 100,
            'keuangan_setelah_efisiensi_percent' => 95,
            'fisik_setelah_efisiensi_percent' => 95,
            'gap_fisik_keuangan' => 0,
        ];
        $this->assertEquals('Aman', $this->invokeMethod($service, 'calculateRisk', [$aman])['status_risiko']);
        $this->assertEquals(0, $this->invokeMethod($service, 'calculateRisk', [$aman])['risk_score']);
    }
}
