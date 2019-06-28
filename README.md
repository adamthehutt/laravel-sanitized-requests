## Laravel Sanitized Requests
Sanitize request input before passing to validation.

#### Installation
```bash
composer require adamthehutt/laravel-sanitized-requests
```

#### Usage
Create a Request class and use the ```SanitizesInput``` trait. Then implement
the ```sanitize(Sanitizer $sanitizer)``` method to clean up user input before it is 
validated.

```php
use AdamTheHutt\SanitizedRequests\SanitizesInput;
use AdamTheHutt\SanitizedRequests\Sanitizer;
use Illuminate\Foundation\Http\FormRequest;

class StoreItem extends FormRequest
{
    use SanitizesInput;

    public function sanitize(Sanitizer $sanitizer): void
    {
        $sanitizer->castToInt("user_id")
                  ->castToBoolean("for_sale")
                  ->castToFloat("price")
                  ->forgetIfEmpty("item_description")
                  ->trimWhitespace("nested.*.wildcard.param");
    }
}
```

#### Sanitizers
The following sanitize methods are currently supported:
 * castToBool($key) - Casts the string input value to a boolean
 * castToInt($key) - Casts the string input value to an integer
 * castToFloat($key) - Casts the string input value to a float
 * forgetIfEmpty($key) - Removes from input both empty strings and arrays with 
 only empty values
 * trimWhitespace($key) - Trims whitespace from a string input value
