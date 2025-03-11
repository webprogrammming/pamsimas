<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

class LoginTest extends TestCase
{
    /**
     * test Login Method
     */

    public function testLoginMethodWorks()
    {
        // Mock LoginRequest
        $loginRequestMock = $this->createMock(LoginRequest::class);

        // Simulasi method authenticate() dipanggil
        $loginRequestMock->expects($this->once())
            ->method('authenticate');

        // Simulasi method session()->regenerate() dipanggil
        $loginRequestMock->expects($this->once())
            ->method('session')
            ->willReturn(new class {
                public function regenerate() {}
            });

        // Buat instance dari controller
        $controller = new AuthenticatedSessionController();

        // Simulasi pemanggilan store
        $response = $controller->store($loginRequestMock);

        // Pastikan hasilnya adalah redirect
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('dashboard'), $response->getTargetUrl());
    }
}
