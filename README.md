# Test for PHP Developer

## Installation

Install with

```bash
$ docker-compose up --buid -d
$ docker exec web bash
$ composer-install
```

## Usage

To send messages with RabbitMq

```bash
$ docker exec web bash
$ php testSend.php
```

To receive messages with RabbitMq and send them to web-server

```bash
$ docker exec web bash
$ php testReceipt.php --host=rabbitmq --port=5672 --user=guest --password=guest --queue=fxtm --batch-size=100 --url=''
```

- Important

Choose the correct or incorrect 'url' in the second command.

Your choice depends on the expected results.

If you want to see mistake in logfile - chose incorrect and vice versa.
