# JiLog - Logging for PHP->Laravel


## Installation

Install the latest version with

```bash
$ composer require baifei2014/plog
```

## Basic Usage

```php
<?php

use JiLog\JiLog;

JiLog::info(
	'request',
	[
		'package_name' => 'baifei2014/jilog',
		'version' => '1.0.0'
	],
	'business_log'
);
```