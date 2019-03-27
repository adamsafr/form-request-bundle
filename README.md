Installation
============

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require adamsafr/form-request-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require adamsafr/form-request-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Adamsafr\FormRequestBundle\AdamsafrFormRequestBundle(),
        ];

        // ...
    }

    // ...
}
```

Configuration
-------------

Create the `adamsafr_form_request.yaml` file in the `config/packages` directory
for Symfony 4 or add it in the `app/config/config.yml` file:

```yaml
adamsafr_form_request:
  request:
    # Decodes json body and replaces your request data.
    # If you're using other packages (e.g. FOSRest) you can turn this option off.
    decode_json_body: true
  exception_listeners:
    access_denied:
      # Sets json response of the Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
      enabled: true
    form_validation:
      # Sets json response of the Adamsafr\FormRequestBundle\Exception\FormValidationException
      enabled: true
    json_decode:
      # Sets json response of the Adamsafr\FormRequestBundle\Exception\JsonDecodeException
      enabled: true
```

Usage
-----

```php
// src/Request/UserRequest.php

namespace App\Request;

use Adamsafr\FormRequestBundle\Request\FormRequest;
use App\Service\Randomizer;
use Symfony\Component\Validator\Constraints as Assert;

class UserRequest extends FormRequest
{
    /**
     * @var Randomizer
     */
    private $randomizer;

    /**
     * You can inject services here
     *
     * @param Randomizer $randomizer
     */
    public function __construct(Randomizer $randomizer)
    {
        $this->randomizer = $randomizer;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->randomizer->getNumber() > 0.5;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return Assert\Collection
     */
    public function rules(): Assert\Collection
    {
        return new Assert\Collection([
            'fields' => [
                'email' => [
                    new Assert\NotBlank(),
                    new Assert\NotNull(),
                    new Assert\Email(),
                ],
                'firstName' => new Assert\Length(['max' => 255]),
                'lastName' => new Assert\Optional([
                    new Assert\Length(['max' => 3]),
                ]),
            ],
        ]);
    }
}
```

```php
// src/Controller/ApiTestController.php

namespace App\Controller;

use App\Request\UserRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiTestController extends AbstractController
{
    public function index(UserRequest $request)
    {
    
    }
}
```
