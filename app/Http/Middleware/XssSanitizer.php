<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class XssSanitizer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

     public function handle(Request $request, Closure $next)
     {
         // Log::debug('Sent from Repo', $request->all());
         $exclude = isset($request->exclude_encrypter) ? $request->exclude_encrypter : "goodtogo";

         if(isset($request->exclude_encrypter)) {
             $exclude = str_replace("[]", "", $exclude);
         }

         if (env('ENCRYPTION_FLAG') == 1) {
             if (isset($request->is_encryted)) {
                 if ($request->is_encryted == 0) {
                     $data = $request->all();

                     foreach ($data as $key => $value) {
                         if ($value != null) {
                             if ($key != $exclude && $key != "exclude_encrypter" && $key != "profile_image" && $key != "is_encryted") {
                                 $request[$key] = Crypt::decryptString($value);
                             }
                         }
                     }
                 }
             }
         }

         $input = $request->all();
         array_walk_recursive($input, function (&$input) {
             $input = strip_tags($input);
         });
         $request->merge($input);

         return $next($request);
     }
}
