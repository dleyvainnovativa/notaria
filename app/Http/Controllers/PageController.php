<?php

namespace App\Http\Controllers;

// Import the Eloquent models we need to interact with the database

use App\Mail\PaymentCompletedMail;
use App\Mail\WelcomeCompletedMail;
use App\Models\Category;
use App\Models\Service;
use App\Models\Branch;
use App\Models\Memorial;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PageController extends Controller
{
    /**
     * Display the home page.
     * Fetches a limited number of services to feature on the homepage.
     */
    public function home()
    {
        $services = [];
        // Pass the services collection to the view.
        return view('pages.home', compact('services'));
    }

    /**
     * Display the main booking page.
     * Fetches all services and categories needed for the booking interface.
     */
    public function book()
    {
        return view('pages.book', []);
    }
    public function memory(Memorial $memorial)
    {
        $memorial->load([
            'user',
            'qrCode',
            'mediaItems',
            'tributes',
            'timelineEvents',
            'paragraphs',
            'payment',
        ]);
        // 👇 filter partners
        $partners = [
            [
                "name" => "Joyerías Bizarro",
                "partner_img" => "https://www.joyeriasbizzarro.com/media/wysiwyg/pulseras_y_gargantillas.png",
                "website" => "https://funerialapaz.com/",
            ],
            [
                "name" => "Larana Florería",
                "partner_img" => "https://i.pinimg.com/736x/e6/04/ce/e604ce26830c8489171e19981a05c664.jpg",
                "website" => "https://www.facebook.com/Laranaonline/",
            ],
            [
                "name" => "Funeraria Con Amor",
                "partner_img" => "https://instagram.fpaz4-1.fna.fbcdn.net/v/t51.82787-15/623383217_17934483606195020_509095373305701569_n.jpg?stp=dst-jpg_e35_tt6&_nc_cat=104&ig_cache_key=MzUwNDc3MzI5NTE0MjMyMDU3OQ%3D%3D.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjk0MHg3ODguc2RyLkMzIn0%3D&_nc_ohc=nDhyS0BunrEQ7kNvwHyJwJU&_nc_oc=Adp7SSrFrhtrN-2JKkvY4kchIm7YCocVnoKwxvi0KeUAm73fJrfWOS6ZilIgyFNrYELpDxjXSuqfaD8W9dcLYLm8&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=instagram.fpaz4-1.fna&_nc_gid=obJWI4-jB3QunthX2KLBTQ&_nc_ss=7a32e&oh=00_Af1aVGlgnhVxjYp08gXIPJcja3NXVGnCQGoYPtLSxrUJPA&oe=69E337DD",
                "website" => "https://www.instagram.com/funerariaconamor/",
            ],
            [
                "name" => "Florería Orgánico",
                "partner_img" => "https://marketplace.canva.com/EAGLD3-IL7I/2/0/1143w/canva-anuncio-florería-venta-de-arreglos-florales-orgánico-morado-FHymnpBXevI.jpg",
                "website" => "https://innovativa.com.mx/",
            ],
            [
                "name" => "El Edén Funeraria",
                "partner_img" => "https://scontent.fpaz4-1.fna.fbcdn.net/v/t39.30808-6/472747155_2758624157649796_7085212115599599751_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=06a7ca&_nc_ohc=1mbgQORqYKgQ7kNvwHBwzGY&_nc_oc=Adq4tXOeDy2I1izDo_17wEhGfQnVM6EaAzsWA31_C9lySjmBwNtQ-WmW4Nr2q4lVzI8Vy-mEQ5je7itSRJAZu7zY&_nc_zt=23&_nc_ht=scontent.fpaz4-1.fna&_nc_gid=q0FEypffpf2dEg_YZrzcFw&_nc_ss=7a3a8&oh=00_Af2BgaA4BBii9fIB_wcxjjh2YgHoK2bn4VTb5FRIfBLp3w&oe=69E331E3",
                "website" => "https://instagram.com/innovativa.mx",
            ],
        ];
        if ($memorial->is_public) {
            return view('pages.memory', compact('memorial', 'partners'));
        } else {
            return view('pages.password', compact('memorial'));
        }
    }
    public function memory_protected(Request $request, Memorial $memorial)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password'       => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $errorData["title"] = "Información incompleta";
                $errorData["subtitle"] = "No se pudo procesar tu solicitud";
                $errorData["message"] = "Por favor, ingresa la contraseña para acceder a este memorial.";
                return view('errors.error', $errorData);
            }
            $memorial->load([
                'user',
                'qrCode',
                'mediaItems',
                'tributes',
                'timelineEvents',
                'paragraphs',
                'payment',
            ]);
            $partners = [
                [
                    "name" => "Joyerías Bizarro",
                    "partner_img" => "https://www.joyeriasbizzarro.com/media/wysiwyg/pulseras_y_gargantillas.png",
                    "website" => "https://funerialapaz.com/",
                ],
                [
                    "name" => "Larana Florería",
                    "partner_img" => "https://i.pinimg.com/736x/e6/04/ce/e604ce26830c8489171e19981a05c664.jpg",
                    "website" => "https://www.facebook.com/Laranaonline/",
                ],
                [
                    "name" => "Funeraria Con Amor",
                    "partner_img" => "https://instagram.fpaz4-1.fna.fbcdn.net/v/t51.82787-15/623383217_17934483606195020_509095373305701569_n.jpg?stp=dst-jpg_e35_tt6&_nc_cat=104&ig_cache_key=MzUwNDc3MzI5NTE0MjMyMDU3OQ%3D%3D.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjk0MHg3ODguc2RyLkMzIn0%3D&_nc_ohc=nDhyS0BunrEQ7kNvwHyJwJU&_nc_oc=Adp7SSrFrhtrN-2JKkvY4kchIm7YCocVnoKwxvi0KeUAm73fJrfWOS6ZilIgyFNrYELpDxjXSuqfaD8W9dcLYLm8&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=instagram.fpaz4-1.fna&_nc_gid=obJWI4-jB3QunthX2KLBTQ&_nc_ss=7a32e&oh=00_Af1aVGlgnhVxjYp08gXIPJcja3NXVGnCQGoYPtLSxrUJPA&oe=69E337DD",
                    "website" => "https://www.instagram.com/funerariaconamor/",
                ],
                [
                    "name" => "Florería Orgánico",
                    "partner_img" => "https://marketplace.canva.com/EAGLD3-IL7I/2/0/1143w/canva-anuncio-florería-venta-de-arreglos-florales-orgánico-morado-FHymnpBXevI.jpg",
                    "website" => "https://innovativa.com.mx/",
                ],
                [
                    "name" => "El Edén Funeraria",
                    "partner_img" => "https://scontent.fpaz4-1.fna.fbcdn.net/v/t39.30808-6/472747155_2758624157649796_7085212115599599751_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=06a7ca&_nc_ohc=1mbgQORqYKgQ7kNvwHBwzGY&_nc_oc=Adq4tXOeDy2I1izDo_17wEhGfQnVM6EaAzsWA31_C9lySjmBwNtQ-WmW4Nr2q4lVzI8Vy-mEQ5je7itSRJAZu7zY&_nc_zt=23&_nc_ht=scontent.fpaz4-1.fna&_nc_gid=q0FEypffpf2dEg_YZrzcFw&_nc_ss=7a3a8&oh=00_Af2BgaA4BBii9fIB_wcxjjh2YgHoK2bn4VTb5FRIfBLp3w&oe=69E331E3",
                    "website" => "https://instagram.com/innovativa.mx",
                ],
            ];
            if ($memorial->is_public) {
                return view('pages.memory', compact('memorial', 'partners'));
            } else {
                $data = $validator->validated();
                if (Hash::check($data["password"], $memorial->access_password)) {
                    return view('pages.memory', compact('memorial', 'partners'));
                } else {
                    $errorData["title"] = "Acceso denegado";
                    $errorData["subtitle"] = "La contraseña no es correcta";
                    $errorData["message"] = "Verifica la contraseña e inténtalo nuevamente para acceder al memorial.";
                    return view('errors.error', $errorData);
                }
            }
        } catch (Exception $e) {
            $errorData["title"] = "Ocurrió un error";
            $errorData["subtitle"] = "No pudimos cargar el memorial";
            $errorData["message"] = "Intenta nuevamente en unos momentos. Si el problema persiste, contacta al administrador.";
            return view('errors.error', $errorData);
        }
    }
    public function test()
    {
        $user = User::find(9);
        $memorial = Memorial::find(1);
        $payment = $memorial->payment()->get()->first();
        $qrCode = $memorial->qrCode()->get()->first();
        $passed["user"] = $user;
        $passed["memorial"] = $memorial;
        $passed["payment"] = $payment;
        $passed["qrCode"] = $qrCode;

        // Mail::to($user->email)->send(
        //     new WelcomeCompletedMail($user)
        // );
        // Mail::to($user->email)->send(
        //     new PaymentCompletedMail($user, $memorial, $payment, $qrCode)
        // );
        // dd($user);
        return view("mail.welcome", $passed);
    }
}
