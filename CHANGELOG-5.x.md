#### 5.0.0-alpha1

* Decoupled test execution and reporting from PHPUnit
* Custom reporters implementing TestListener are no longer supported and must be converted to Extensions
* Tests of all formats are reported as useless if they perform no assertions and `reports_useless_tests` setting is enabled
* Added path_coverage setting to enable path and branch coverage #6158 by @s0lus
* Added optional value to `fail-fast` option (#6275) by #Verest
* Removed `JSON` and `TAP` loggers
* Removed code coverage blacklist functionality
* Removed deprecated class aliases
  - Codeception\TestCase\Test
  - Codeception\Platform\Group
  - Codeception\Platform\Group
  - Codeception\TestCase
* Introduced strict types in the code base.
* Compatible with PHPUnit 10 only
* Compatible with Symfony 4.4 - 6.0
* Requires PHP 8.0 or higher

#### 5.0.0-alpha2

* Generators create namespaced test suites by default (#6071) by @DavertMik
* Test can be filtered by data provider case number or name (#6363) by @Naktibalda
* Removed `generate:cept` command (Cept format is deprecated)
* Removed settings `disallow_test_output` and `log_incomplete_skipped`.
* Removed setting `paths.log` (it was replaced by `paths.output` in Codeception 2.3)
* Removed suite setting `class_name` (replaced by `actor` in Codeception 2.3)
* Removed global setting `actor` (replaced by `actor_prefix` in Codeception 2.3)
* Removed `Configuration::logDir` method (replaced by `Configuration::logDir` in 2.0)
* ParamLoader throws exception if xml file is used but simplexml extension is missing (#6346) by @mdoelker
* Updated codebase to use PHP 8.0 features by @TavoNiievez
* Don't report test as useless if it failed (fixed bug introduced in alpha1)
* Don't report local test coverage for remote suites (fixed bug introduced in alpha1)
* Moved XmlBuilder class to module-soap

