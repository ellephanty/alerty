<?php

/** @var array $errorData */
/** @var string $traceHtml */

?>

<html>

<head>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
        }

        h2 {
            color: #c0392b;
        }

        .box {
            background: #f8f8f8;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>🚨 Exception Report</h2>

        <div class="box">
            <strong>Environment:</strong> <?= $errorData['environment'] ?><br>
            <strong>Message:</strong> <?= $errorData['message'] ?><br>
            <strong>File:</strong> <?= $errorData['file'] ?><br>
            <strong>Line:</strong> <?= $errorData['line'] ?><br>
        </div>

        <?php if (!empty($errorData['extra'])): ?>

            <h3>Extra data</h3>
            <div class="box">
                <?php foreach ($errorData['extra'] as $key => $value): ?>
                    <strong><?php echo $key; ?>:</strong>
                    <?php echo is_array($value) ? json_encode($value) : $value; ?>
                    <br>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

        <h3>Stack trace</h3>
        <ul>
            <?= $traceHtml ?>
        </ul>
    </div>

</body>

</html>