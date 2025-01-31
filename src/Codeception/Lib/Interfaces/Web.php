<?php

namespace Codeception\Lib\Interfaces;

interface Web
{
    /**
     * Opens the page for the given relative URI.
     *
     * ``` php
     * <?php
     * // opens front page
     * $I->amOnPage('/');
     * // opens /register page
     * $I->amOnPage('/register');
     * ```
     */
    public function amOnPage(string $page): void;

    /**
     * Checks that the current page contains the given string (case insensitive).
     *
     * You can specify a specific HTML element (via CSS or XPath) as the second
     * parameter to only search within that element.
     *
     * ``` php
     * <?php
     * $I->see('Logout');                        // I can suppose user is logged in
     * $I->see('Sign Up', 'h1');                 // I can suppose it's a signup page
     * $I->see('Sign Up', '//body/h1');          // with XPath
     * $I->see('Sign Up', ['css' => 'body h1']); // with strict CSS locator
     * ```
     *
     * Note that the search is done after stripping all HTML tags from the body,
     * so `$I->see('strong')` will return true for strings like:
     *
     *   - `<p>I am Stronger than thou</p>`
     *   - `<script>document.createElement('strong');</script>`
     *
     * But will *not* be true for strings like:
     *
     *   - `<strong>Home</strong>`
     *   - `<div class="strong">Home</strong>`
     *   - `<!-- strong -->`
     *
     * For checking the raw source code, use `seeInSource()`.
     *
     * @param array|string $selector optional
     */
    public function see(string $text, $selector = null): void;

    /**
     * Checks that the current page doesn't contain the text specified (case insensitive).
     * Give a locator as the second parameter to match a specific region.
     *
     * ```php
     * <?php
     * $I->dontSee('Login');                         // I can suppose user is already logged in
     * $I->dontSee('Sign Up','h1');                  // I can suppose it's not a signup page
     * $I->dontSee('Sign Up','//body/h1');           // with XPath
     * $I->dontSee('Sign Up', ['css' => 'body h1']); // with strict CSS locator
     * ```
     *
     * Note that the search is done after stripping all HTML tags from the body,
     * so `$I->dontSee('strong')` will fail on strings like:
     *
     *   - `<p>I am Stronger than thou</p>`
     *   - `<script>document.createElement('strong');</script>`
     *
     * But will ignore strings like:
     *
     *   - `<strong>Home</strong>`
     *   - `<div class="strong">Home</strong>`
     *   - `<!-- strong -->`
     *
     * For checking the raw source code, use `seeInSource()`.
     *
     * @param array|string $selector optional
     */
    public function dontSee(string $text, $selector = null): void;

    /**
     * Checks that the current page contains the given string in its
     * raw source code.
     *
     * ``` php
     * <?php
     * $I->seeInSource('<h1>Green eggs &amp; ham</h1>');
     * ```
     */
    public function seeInSource(string $raw): void;

    /**
     * Checks that the current page contains the given string in its
     * raw source code.
     *
     * ```php
     * <?php
     * $I->dontSeeInSource('<h1>Green eggs &amp; ham</h1>');
     * ```
     */
    public function dontSeeInSource(string $raw): void;

    /**
     * Submits the given form on the page, with the given form
     * values.  Pass the form field's values as an array in the second
     * parameter.
     *
     * Although this function can be used as a short-hand version of
     * `fillField()`, `selectOption()`, `click()` etc. it has some important
     * differences:
     *
     *  * Only field *names* may be used, not CSS/XPath selectors nor field labels
     *  * If a field is sent to this function that does *not* exist on the page,
     *    it will silently be added to the HTTP request.  This is helpful for testing
     *    some types of forms, but be aware that you will *not* get an exception
     *    like you would if you called `fillField()` or `selectOption()` with
     *    a missing field.
     *
     * Fields that are not provided will be filled by their values from the page,
     * or from any previous calls to `fillField()`, `selectOption()` etc.
     * You don't need to click the 'Submit' button afterwards.
     * This command itself triggers the request to form's action.
     *
     * You can optionally specify which button's value to include
     * in the request with the last parameter (as an alternative to
     * explicitly setting its value in the second parameter), as
     * button values are not otherwise included in the request.
     *
     * Examples:
     *
     * ``` php
     * <?php
     * $I->submitForm('#login', [
     *     'login' => 'davert',
     *     'password' => '123456'
     * ]);
     * // or
     * $I->submitForm('#login', [
     *     'login' => 'davert',
     *     'password' => '123456'
     * ], 'submitButtonName');
     *
     * ```
     *
     * For example, given this sample "Sign Up" form:
     *
     * ``` html
     * <form id="userForm">
     *     Login:
     *     <input type="text" name="user[login]" /><br/>
     *     Password:
     *     <input type="password" name="user[password]" /><br/>
     *     Do you agree to our terms?
     *     <input type="checkbox" name="user[agree]" /><br/>
     *     Subscribe to our newsletter?
     *     <input type="checkbox" name="user[newsletter]" value="1" checked="checked" /><br/>
     *     Select pricing plan:
     *     <select name="plan">
     *         <option value="1">Free</option>
     *         <option value="2" selected="selected">Paid</option>
     *     </select>
     *     <input type="submit" name="submitButton" value="Submit" />
     * </form>
     * ```
     *
     * You could write the following to submit it:
     *
     * ``` php
     * <?php
     * $I->submitForm(
     *     '#userForm',
     *     [
     *         'user' => [
     *             'login' => 'Davert',
     *             'password' => '123456',
     *             'agree' => true
     *         ]
     *     ],
     *     'submitButton'
     * );
     * ```
     * Note that "2" will be the submitted value for the "plan" field, as it is
     * the selected option.
     *
     * To uncheck the pre-checked checkbox "newsletter", call `$I->uncheckOption(['name' => 'user[newsletter]']);` *before*,
     * then submit the form as shown here (i.e. without the "newsletter" field in the `$params` array).
     *
     * You can also emulate a JavaScript submission by not specifying any
     * buttons in the third parameter to submitForm.
     *
     * ```php
     * <?php
     * $I->submitForm(
     *     '#userForm',
     *     [
     *         'user' => [
     *             'login' => 'Davert',
     *             'password' => '123456',
     *             'agree' => true
     *         ]
     *     ]
     * );
     * ```
     *
     * This function works well when paired with `seeInFormFields()`
     * for quickly testing CRUD interfaces and form validation logic.
     *
     * ``` php
     * <?php
     * $form = [
     *      'field1' => 'value',
     *      'field2' => 'another value',
     *      'checkbox1' => true,
     *      // ...
     * ];
     * $I->submitForm('#my-form', $form, 'submitButton');
     * // $I->amOnPage('/path/to/form-page') may be needed
     * $I->seeInFormFields('#my-form', $form);
     * ```
     *
     * Parameter values can be set to arrays for multiple input fields
     * of the same name, or multi-select combo boxes.  For checkboxes,
     * you can use either the string value or boolean `true`/`false` which will
     * be replaced by the checkbox's value in the DOM.
     *
     * ``` php
     * <?php
     * $I->submitForm('#my-form', [
     *      'field1' => 'value',
     *      'checkbox' => [
     *          'value of first checkbox',
     *          'value of second checkbox',
     *      ],
     *      'otherCheckboxes' => [
     *          true,
     *          false,
     *          false
     *      ],
     *      'multiselect' => [
     *          'first option value',
     *          'second option value'
     *      ]
     * ]);
     * ```
     *
     * Mixing string and boolean values for a checkbox's value is not supported
     * and may produce unexpected results.
     *
     * Field names ending in `[]` must be passed without the trailing square
     * bracket characters, and must contain an array for its value.  This allows
     * submitting multiple values with the same name, consider:
     *
     * ```php
     * <?php
     * // This will NOT work correctly
     * $I->submitForm('#my-form', [
     *     'field[]' => 'value',
     *     'field[]' => 'another value',  // 'field[]' is already a defined key
     * ]);
     * ```
     *
     * The solution is to pass an array value:
     *
     * ```php
     * <?php
     * // This way both values are submitted
     * $I->submitForm('#my-form', [
     *     'field' => [
     *         'value',
     *         'another value',
     *     ]
     * ]);
     * ```
     */
    public function submitForm($selector, array $params, string $button = null): void;

    /**
     * Perform a click on a link or a button, given by a locator.
     * If a fuzzy locator is given, the page will be searched for a button, link, or image matching the locator string.
     * For buttons, the "value" attribute, "name" attribute, and inner text are searched.
     * For links, the link text is searched.
     * For images, the "alt" attribute and inner text of any parent links are searched.
     *
     * The second parameter is a context (CSS or XPath locator) to narrow the search.
     *
     * Note that if the locator matches a button of type `submit`, the form will be submitted.
     *
     * ``` php
     * <?php
     * // simple link
     * $I->click('Logout');
     * // button of form
     * $I->click('Submit');
     * // CSS button
     * $I->click('#form input[type=submit]');
     * // XPath
     * $I->click('//form/*[@type="submit"]');
     * // link in context
     * $I->click('Logout', '#nav');
     * // using strict locator
     * $I->click(['link' => 'Login']);
     * ```
     * @param string|array $link
     */
    public function click($link, $context = null): void;

    /**
     * Checks that there's a link with the specified text.
     * Give a full URL as the second parameter to match links with that exact URL.
     *
     * ``` php
     * <?php
     * $I->seeLink('Logout'); // matches <a href="#">Logout</a>
     * $I->seeLink('Logout','/logout'); // matches <a href="/logout">Logout</a>
     * ```
     */
    public function seeLink(string $text, string $url = null): void;

    /**
     * Checks that the page doesn't contain a link with the given string.
     * If the second parameter is given, only links with a matching "href" attribute will be checked.
     *
     * ``` php
     * <?php
     * $I->dontSeeLink('Logout'); // I suppose user is not logged in
     * $I->dontSeeLink('Checkout now', '/store/cart.php');
     * ```
     */
    public function dontSeeLink(string $text, string $url = ''): void;

    /**
     * Checks that current URI contains the given string.
     *
     * ``` php
     * <?php
     * // to match: /home/dashboard
     * $I->seeInCurrentUrl('home');
     * // to match: /users/1
     * $I->seeInCurrentUrl('/users/');
     * ```
     */
    public function seeInCurrentUrl(string $uri): void;

    /**
     * Checks that the current URL is equal to the given string.
     * Unlike `seeInCurrentUrl`, this only matches the full URL.
     *
     * ``` php
     * <?php
     * // to match root url
     * $I->seeCurrentUrlEquals('/');
     * ```
     */
    public function seeCurrentUrlEquals(string $uri): void;

    /**
     * Checks that the current URL matches the given regular expression.
     *
     * ``` php
     * <?php
     * // to match root url
     * $I->seeCurrentUrlMatches('~^/users/(\d+)~');
     * ```
     */
    public function seeCurrentUrlMatches(string $uri): void;

    /**
     * Checks that the current URI doesn't contain the given string.
     *
     * ``` php
     * <?php
     * $I->dontSeeInCurrentUrl('/users/');
     * ```
     */
    public function dontSeeInCurrentUrl(string $uri): void;

    /**
     * Checks that the current URL doesn't equal the given string.
     * Unlike `dontSeeInCurrentUrl`, this only matches the full URL.
     *
     * ``` php
     * <?php
     * // current url is not root
     * $I->dontSeeCurrentUrlEquals('/');
     * ```
     */
    public function dontSeeCurrentUrlEquals(string $uri): void;

    /**
     * Checks that current url doesn't match the given regular expression.
     *
     * ``` php
     * <?php
     * // to match root url
     * $I->dontSeeCurrentUrlMatches('~^/users/(\d+)~');
     * ```
     */
    public function dontSeeCurrentUrlMatches(string $uri): void;

    /**
     * Executes the given regular expression against the current URI and returns the first capturing group.
     * If no parameters are provided, the full URI is returned.
     *
     * ``` php
     * <?php
     * $user_id = $I->grabFromCurrentUrl('~^/user/(\d+)/~');
     * $uri = $I->grabFromCurrentUrl();
     * ```
     */
    public function grabFromCurrentUrl(string $uri = null): mixed;

    /**
     * Checks that the specified checkbox is checked.
     *
     * ``` php
     * <?php
     * $I->seeCheckboxIsChecked('#agree'); // I suppose user agreed to terms
     * $I->seeCheckboxIsChecked('#signup_form input[type=checkbox]'); // I suppose user agreed to terms, If there is only one checkbox in form.
     * $I->seeCheckboxIsChecked('//form/input[@type=checkbox and @name=agree]');
     * ```
     */
    public function seeCheckboxIsChecked($checkbox): void;

    /**
     * Check that the specified checkbox is unchecked.
     *
     * ``` php
     * <?php
     * $I->dontSeeCheckboxIsChecked('#agree'); // I suppose user didn't agree to terms
     * $I->seeCheckboxIsChecked('#signup_form input[type=checkbox]'); // I suppose user didn't check the first checkbox in form.
     * ```
     */
    public function dontSeeCheckboxIsChecked(string $checkbox): void;

    /**
     * Checks that the given input field or textarea *equals* (i.e. not just contains) the given value.
     * Fields are matched by label text, the "name" attribute, CSS, or XPath.
     *
     * ``` php
     * <?php
     * $I->seeInField('Body','Type your comment here');
     * $I->seeInField('form textarea[name=body]','Type your comment here');
     * $I->seeInField('form input[type=hidden]','hidden_value');
     * $I->seeInField('#searchform input','Search');
     * $I->seeInField('//form/*[@name=search]','Search');
     * $I->seeInField(['name' => 'search'], 'Search');
     * ```
     *
     * @param string|array $field
     */
    public function seeInField($field, $value): void;

    /**
     * Checks that an input field or textarea doesn't contain the given value.
     * For fuzzy locators, the field is matched by label text, CSS and XPath.
     *
     * ``` php
     * <?php
     * $I->dontSeeInField('Body','Type your comment here');
     * $I->dontSeeInField('form textarea[name=body]','Type your comment here');
     * $I->dontSeeInField('form input[type=hidden]','hidden_value');
     * $I->dontSeeInField('#searchform input','Search');
     * $I->dontSeeInField('//form/*[@name=search]','Search');
     * $I->dontSeeInField(['name' => 'search'], 'Search');
     * ```
     * @param string|array $field
     */
    public function dontSeeInField($field, $value): void;

    /**
     * Checks if the array of form parameters (name => value) are set on the form matched with the
     * passed selector.
     *
     * ``` php
     * <?php
     * $I->seeInFormFields('form[name=myform]', [
     *      'input1' => 'value',
     *      'input2' => 'other value',
     * ]);
     * ```
     *
     * For multi-select elements, or to check values of multiple elements with the same name, an
     * array may be passed:
     *
     * ``` php
     * <?php
     * $I->seeInFormFields('.form-class', [
     *      'multiselect' => [
     *          'value1',
     *          'value2',
     *      ],
     *      'checkbox[]' => [
     *          'a checked value',
     *          'another checked value',
     *      ],
     * ]);
     * ```
     *
     * Additionally, checkbox values can be checked with a boolean.
     *
     * ``` php
     * <?php
     * $I->seeInFormFields('#form-id', [
     *      'checkbox1' => true,        // passes if checked
     *      'checkbox2' => false,       // passes if unchecked
     * ]);
     * ```
     *
     * Pair this with submitForm for quick testing magic.
     *
     * ``` php
     * <?php
     * $form = [
     *      'field1' => 'value',
     *      'field2' => 'another value',
     *      'checkbox1' => true,
     *      // ...
     * ];
     * $I->submitForm('//form[@id=my-form]', string $form, 'submitButton');
     * // $I->amOnPage('/path/to/form-page') may be needed
     * $I->seeInFormFields('//form[@id=my-form]', string $form);
     * ```
     */
    public function seeInFormFields($formSelector, array $params): void;

    /**
     * Checks if the array of form parameters (name => value) are not set on the form matched with
     * the passed selector.
     *
     * ``` php
     * <?php
     * $I->dontSeeInFormFields('form[name=myform]', [
     *      'input1' => 'non-existent value',
     *      'input2' => 'other non-existent value',
     * ]);
     * ```
     *
     * To check that an element hasn't been assigned any one of many values, an array can be passed
     * as the value:
     *
     * ``` php
     * <?php
     * $I->dontSeeInFormFields('.form-class', [
     *      'fieldName' => [
     *          'This value shouldn\'t be set',
     *          'And this value shouldn\'t be set',
     *      ],
     * ]);
     * ```
     *
     * Additionally, checkbox values can be checked with a boolean.
     *
     * ``` php
     * <?php
     * $I->dontSeeInFormFields('#form-id', [
     *      'checkbox1' => true,        // fails if checked
     *      'checkbox2' => false,       // fails if unchecked
     * ]);
     * ```
     */
    public function dontSeeInFormFields($formSelector, array $params): void;

    /**
     * Selects an option in a select tag or in radio button group.
     *
     * ``` php
     * <?php
     * $I->selectOption('form select[name=account]', 'Premium');
     * $I->selectOption('form input[name=payment]', 'Monthly');
     * $I->selectOption('//form/select[@name=account]', 'Monthly');
     * ```
     *
     * Provide an array for the second argument to select multiple options:
     *
     * ``` php
     * <?php
     * $I->selectOption('Which OS do you use?', array('Windows','Linux'));
     * ```
     *
     * Or provide an associative array for the second argument to specifically define which selection method should be used:
     *
     * ``` php
     * <?php
     * $I->selectOption('Which OS do you use?', array('text' => 'Windows')); // Only search by text 'Windows'
     * $I->selectOption('Which OS do you use?', array('value' => 'windows')); // Only search by value 'windows'
     * ```
     */
    public function selectOption($select, $option): void;

    /**
     * Ticks a checkbox. For radio buttons, use the `selectOption` method instead.
     *
     * ``` php
     * <?php
     * $I->checkOption('#agree');
     * ```
     */
    public function checkOption($option): void;

    /**
     * Unticks a checkbox.
     *
     * ``` php
     * <?php
     * $I->uncheckOption('#notify');
     * ```
     */
    public function uncheckOption($option): void;

    /**
     * Fills a text field or textarea with the given string.
     *
     * ``` php
     * <?php
     * $I->fillField("//input[@type='text']", "Hello World!");
     * $I->fillField(['name' => 'email'], 'jon@example.com');
     * ```
     * @param string|array $field
     */
    public function fillField($field, $value): void;

    /**
     * Attaches a file relative to the Codeception `_data` directory to the given file upload field.
     *
     * ``` php
     * <?php
     * // file is stored in 'tests/_data/prices.xls'
     * $I->attachFile('input[@type="file"]', 'prices.xls');
     * ```
     */
    public function attachFile($field, string $filename): void;

    /**
     * Finds and returns the text contents of the given element.
     * If a fuzzy locator is used, the element is found using CSS, XPath,
     * and by matching the full page source by regular expression.
     *
     * ``` php
     * <?php
     * $heading = $I->grabTextFrom('h1');
     * $heading = $I->grabTextFrom('descendant-or-self::h1');
     * $value = $I->grabTextFrom('~<input value=(.*?)]~sgi'); // match with a regex
     * ```
     */
    public function grabTextFrom($cssOrXPathOrRegex): mixed;

    /**
     * Finds the value for the given form field.
     * If a fuzzy locator is used, the field is found by field name, CSS, and XPath.
     *
     * ``` php
     * <?php
     * $name = $I->grabValueFrom('Name');
     * $name = $I->grabValueFrom('input[name=username]');
     * $name = $I->grabValueFrom('descendant-or-self::form/descendant::input[@name = 'username']');
     * $name = $I->grabValueFrom(['name' => 'username']);
     * ```
     */
    public function grabValueFrom($field): mixed;

    /**
     * Grabs the value of the given attribute value from the given element.
     * Fails if element is not found.
     *
     * ``` php
     * <?php
     * $I->grabAttributeFrom('#tooltip', 'title');
     * ```
     */
    public function grabAttributeFrom($cssOrXpath, string $attribute): mixed;

    /**
     * Grabs either the text content, or attribute values, of nodes
     * matched by $cssOrXpath and returns them as an array.
     *
     * ```html
     * <a href="#first">First</a>
     * <a href="#second">Second</a>
     * <a href="#third">Third</a>
     * ```
     *
     * ```php
     * <?php
     * // would return ['First', 'Second', 'Third']
     * $aLinkText = $I->grabMultiple('a');
     *
     * // would return ['#first', '#second', '#third']
     * $aLinks = $I->grabMultiple('a', 'href');
     * ```
     *
     * @return string[]
     */
    public function grabMultiple($cssOrXpath, string $attribute = null): array;

    /**
     * Checks that the given element exists on the page and is visible.
     * You can also specify expected attributes of this element.
     *
     * ``` php
     * <?php
     * $I->seeElement('.error');
     * $I->seeElement('//form/input[1]');
     * $I->seeElement('input', ['name' => 'login']);
     * $I->seeElement('input', ['value' => '123456']);
     *
     * // strict locator in first arg, attributes in second
     * $I->seeElement(['css' => 'form input'], ['name' => 'login']);
     * ```
     */
    public function seeElement($selector, array $attributes = []): void;

    /**
     * Checks that the given element is invisible or not present on the page.
     * You can also specify expected attributes of this element.
     *
     * ``` php
     * <?php
     * $I->dontSeeElement('.error');
     * $I->dontSeeElement('//form/input[1]');
     * $I->dontSeeElement('input', ['name' => 'login']);
     * $I->dontSeeElement('input', ['value' => '123456']);
     * ```
     */
    public function dontSeeElement($selector, array $attributes = []): void;

    /**
     * Checks that there are a certain number of elements matched by the given locator on the page.
     *
     * ``` php
     * <?php
     * $I->seeNumberOfElements('tr', 10);
     * $I->seeNumberOfElements('tr', [0,10]); // between 0 and 10 elements
     * ```
     *
     * @param int|int[] $expected
     */
    public function seeNumberOfElements($selector, array|int $expected): void;

    /**
     * Checks that the given option is selected.
     *
     * ``` php
     * <?php
     * $I->seeOptionIsSelected('#form input[name=payment]', 'Visa');
     * ```
     *
     * @return mixed|void
     */
    public function seeOptionIsSelected($selector, string $optionText);

    /**
     * Checks that the given option is not selected.
     *
     * ``` php
     * <?php
     * $I->dontSeeOptionIsSelected('#form input[name=payment]', 'Visa');
     * ```
     *
     * @return mixed|void
     */
    public function dontSeeOptionIsSelected($selector, string $optionText);

    /**
     * Checks that the page title contains the given string.
     *
     * ``` php
     * <?php
     * $I->seeInTitle('Blog - Post #1');
     * ```
     *
     * @return mixed|void
     */
    public function seeInTitle(string $title);

    /**
     * Checks that the page title does not contain the given string.
     *
     * @return mixed|void
     */
    public function dontSeeInTitle(string $title);

    /**
     * Checks that a cookie with the given name is set.
     * You can set additional cookie params like `domain`, `path` as array passed in last argument.
     *
     * ``` php
     * <?php
     * $I->seeCookie('PHPSESSID');
     * ```
     *
     * @return mixed|void
     */
    public function seeCookie(string $cookie, array $params = []);

    /**
     * Checks that there isn't a cookie with the given name.
     * You can set additional cookie params like `domain`, `path` as array passed in last argument.
     *
     * @return mixed|void
     */
    public function dontSeeCookie(string $cookie, array $params = []);

    /**
     * Sets a cookie with the given name and value.
     * You can set additional cookie params like `domain`, `path`, `expires`, `secure` in array passed as last argument.
     *
     * ``` php
     * <?php
     * $I->setCookie('PHPSESSID', 'el4ukv0kqbvoirg7nkp4dncpk3');
     * ```
     *
     * @return mixed|void
     */
    public function setCookie(string $name, ?string $val, array $params = []);

    /**
     * Unsets cookie with the given name.
     * You can set additional cookie params like `domain`, `path` in array passed as last argument.
     *
     * @return mixed|void
     */
    public function resetCookie(string $cookie, array $params = []);

    /**
     * Grabs a cookie value.
     * You can set additional cookie params like `domain`, `path` in array passed as last argument.
     * If the cookie is set by an ajax request (XMLHttpRequest), there might be some delay caused by the browser, so try `$I->wait(0.1)`.
     */
    public function grabCookie(string $cookie, array $params = []): mixed;

    /**
     * Grabs current page source code.
     *
     * @return string Current page source code.
     */
    public function grabPageSource(): string;
}
