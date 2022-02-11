<html>

<head>

  <style>
    * {
      font-family: Arial, Helvetica, sans-serif;
    }

    h2,
    h3 {
      margin: 1rem 0;
    }

    table {
      border-collapse: collapse;
    }

    table,
    th,
    td {
      border: none;
    }

    th,
    td {
      padding: 5px;
    }

    table.list {
      width: 100%;
    }

    table.list,
    table.list th,
    table.list td {
      border: 1px solid #cccccc;
    }

    div.page_break+div.page_break {
      page-break-before: always;
    }
  </style>
</head>

<body>
  <?= $content ?>
</body>

</html>
