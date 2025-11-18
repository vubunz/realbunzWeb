<?php
include './main.php';
?>

<?php
require_once("CMain/connect.php");
$sql = "SELECT * FROM `item` ORDER BY `id`";
$query = mysqli_query($conn, $sql);
?>
<style type="text/css">
      /*  table, th, td{
            border:1px solid #868585;
        }
        table{
            border-collapse:collapse;
            width:100%;
        }
        th, td{
            text-align:left;
            padding:10px;
        }
        table tr:nth-child(odd){
            background-color:#eee;
        }
        table tr:nth-child(even){
            background-color:white;
        }*/
        table tr:nth-child(1){
            background-color:#FFB475;/*màu thanh trên bảng nạp*/
        }
        
div#nz-div {
    border-bottom: 2px solid red;
    margin-bottom: 40px;
    display: block;
}
 
#nz-div h3.tde {
    margin: 0;
    font-size: 16px;
    line-height: 20px;
    display: inline-block;
    text-transform: uppercase;
}
 
#nz-div h3.tde :after {
    content: "";
    width: 0;
    height: 0;
    border-top: 40px solid transparent;
    border-left: 20px solid #EA3A3C;
    border-bottom: 0px solid transparent;
    border-right: 0 solid transparent;
    position: absolute;
    top: 0px;
    right: -20px;
}
 
#nz-div h3.tde span {
    background: #EA3A3C;
    padding: 10px 20px 8px 20px;
    color: white;
    position: relative;
    display: inline-block;
    margin: 0;
}
 
.sub-cat {
    display: inline-block;
    margin: 0;
    line-height: 45px;
    margin-left: 100px;
    float: right;
}
 
/* 2  ========================= */

#nz-div-2 h3.tde :after {
    content: "";
    width: 0;
    height: 0;
    border-top: 20px solid transparent;
    border-left: 15px solid #EA3A3C;
    border-bottom: 19px solid transparent;
    border-right: 0 solid transparent;
    position: absolute;
    top: 0px;
    right: -15px;
}
 
#nz-div-2 h3.tde span {
    background: #EA3A3C;
    padding: 10px 20px 8px 20px;
    color: white;
    position: relative;
    display: inline-block;
    margin: 0;
}
 
#nz-div-2 h3.tde {
    margin: 15px 0;
    font-size: 16px;
    line-height: 20px;
    text-transform: uppercase;
}
 


/*
  #nz-div-2 table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }

  #nz-div-2 th, #nz-div-2 td {
    padding: 10px;
    text-align: center;
    font-size: 16px;
  }

  #nz-div-2 th {
    background-color: #f2f2f2;
  }

  #nz-div-2 td {
    border-bottom: 1px solid #ddd;
  }
*/
/* 3  ========================= */
 
#nz-div-3 h3.tde span {
    background: #EA3A3C;
    padding: 10px 20px 8px 20px;
    color: white;
    position: relative;
    display: inline-block;
    margin: 0;
    border-radius: 23px 23px 0px 0px;
}
 
#nz-div-3 h3.tde {
    margin: 15px 0;
    border-bottom: 2px solid #ea3a3c;
    font-size: 16px;
    line-height: 20px;
    text-transform: uppercase;
}
 
/* 4  ========================= */
 
#nz-div-4 h3.tde :after {
    content: "";
    width: 0;
    height: 0;
    border-top: 40px solid transparent;
    border-left: 20px solid #EA3A3C;
    border-bottom: 0px solid transparent;
    border-right: 0 solid transparent;
    position: absolute;
    top: 0px;
    right: -20px;
}
 
 
#nz-div-4 h3.tde :before {
    content: "";
    width: 0;
    height: 0;
    border-width: 40px 20px 0px 0px;
    border-style: solid;
    border-color: transparent;
    border-right-color: #EA3A3C;
    position: absolute;  
    top: 0px;
    left: -20px;
}
 
#nz-div-4 h3.tde span {
    background: #EA3A3C;
    padding: 10px 20px 8px 20px;
    color: white;
    position: relative;
    display: inline-block;
    margin: 0;
  
}
 
#nz-div-4 h3.tde {
    text-align: center;
    margin: 45px 0;
    border-bottom: 2px solid #ea3a3c;
    font-size: 16px;
    line-height: 20px;
    text-transform: uppercase;
}
 
/* 5  ========================= */
 
#nz-div-5 {
  text-align: center;
}
 
#nz-div-5 h3.tde {
    display: inline-block;
    background: #ea3a3c;
    color: white;
    height: 50px;
    line-height: 50px;
    padding: 0 30px;
  width: auto;
}
  
#nz-div-5 h3.tde span:before {
    content: '';
    width: 80px;
    height: 70px;
    z-index: -1; 
}
 
/* ======================================= */
.title-comm {
    color: #fff;
    font-size: 18px;
    position: relative;
    margin-top: 30px;
    margin-bottom: 30px;
    font-weight: 700;
    display: block;
    text-align: center;
}

h3.title-comm:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    margin-top: 0;
    z-index: 1;
    display: block;
}

.title-comm .title-holder {
    min-width: 350px;
    height: 45px;
    background-color: #ea3a3c;
    line-height: 45px;
    padding: 0px 20px;
    position: relative;
    z-index: 2;
    text-align: center;
    display: inline-block;
    min-width: 280px;
}

 
.title-holder:before {
    content: "";
    position: absolute;
    right: -15px;
    border-width: 0px;
    bottom: 0px;
    border-style: solid;
    border-color: #5c9efe transparent;
    display: block;
    width: 0;
    height: 0;
    border-top: 23px solid transparent;
    border-bottom: 22px solid transparent;
    border-left: 15px solid #ea3a3c;
}
 
.title-holder:after {
    content: "";
    position: absolute;
    left: -15px;
    border-width: 0px;
    bottom: 0px;
    border-style: solid;
    border-color: #5c9efe transparent;
    display: block;
    width: 0;
    height: 0;
    border-top: 23px solid transparent;
    border-bottom: 22px solid transparent;
    border-right: 15px solid #ea3a3c;
}

    /* Thêm đoạn mã CSS mới dưới đây */
    #customTable th {
        background-color: #FFB475;
        color: white;
    }
    </style>
<style>
  body {
    margin: 0; /* Loại bỏ margin của body */
  }
 /* .hoandzvc {
    border-collapse: collapse;
    width: 100%;
    border-radius: 10px; 
    overflow: hidden; 
  }
  .hoandzvc {
    border-collapse: collapse;
    width: 100%;
  }

 

  .hoandzvc tbody tr:nth-child(odd) {
    background-color: #f9f9f9;
  }*/
  .hoandzvc th, .hoandzvc td {
    border: 1px solid #dee2e6; 
  }
  .hoandzvc tbody tr:nth-child(even) {
    background-color: #fff;
  }

  .hoandzvc tbody tr:nth-child(1) {
    background-color: #e1ecc8;
  }

  .hoandzvc tbody tr:nth-child(2) {
    background-color: #e1ecc8;
  }

  .hoandzvc tbody tr:nth-child(3) {
    background-color: #e1ecc8;
  }

  .hoandzvc tbody tr:nth-child(4) {
    background-color: #f2f2f2;
  }

  .hoandzvc th, .hoandzvc td {
    height: 40px; /* Điều chỉnh chiều cao cột */
  }
</style>


<style>
    body, h1, h2, h3, h4, h5, h6, p, table, th, td {
        margin: 0;
        padding: 0;
    }

    #itemTable {
        margin: 0;
        border-collapse: collapse;
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
    }

    #itemTable th,
    #itemTable td {
        padding: 8px;
        text-align: left;
        border-right: 1px solid #FFB475;
    }

    #itemTable tbody tr {
        border-bottom: 1px solid #FFB475;
    }

    #itemTable tbody tr:last-child {
        border-bottom: none;
    }

    #itemTable th:last-child,
    #itemTable td:last-child {
        border-right: none;
    }

    #itemTable thead th {
        background-color: #FFB475;
        color: white;
    }

    #itemTable tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #itemTable tbody tr:nth-child(odd) {
        background-color: #e7e7e7;
    }


    .center-search {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 10px;
    }

    .search-container {
        position: relative;
        display: inline-block;
    }

    .search-container input[type="text"] {
        width: 350px;
        height: 30px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .search-container i {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
    }

    @media only screen and (max-width: 600px) {
        .search-container input[type="text"] {
            width: 100%; /* Thiết lập chiều rộng là 100% trên màn hình có chiều rộng tối đa 600px */
        }
    }



    .card1 {
        width: 100%;
        overflow: hidden;
    }

    .card-body1 {
        overflow-x: auto;
    }
</style>

<div class="card">
<br>
        <center>

        <h2 style="color: #ff8400;">🍑️Danh sách Item🍑️</h2>

        </center>
</br>
        <hr>
        <div class="center-search">
            <div class="mb-2">
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Tìm item...">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>

        <div class="card1">
            <div class="card-body1">
                <table id="itemTable" class="table table-bordered table-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Mô tả</th>
                            <th>Cấp độ</th>
                            <th>Giới tính</th>
                            <th>Xếp chồng</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_array($query)) {
                            if ($row['gender'] == 2) {
                                $gender = "Cả 2";
                            }
                            if ($row['gender'] == 1) {
                                $gender = "Nam";
                            }
                            if ($row['gender'] == 0) {
                                $gender = "Nữ";
                            }
                            if ($row['uptoup'] == 0) {
                                $isUpToUp = "✘";
                            }
                            if ($row['uptoup'] == 1) {
                                $isUpToUp = "✓";
                            }
                            echo "<tr><td>" . $row['id'] . " </td><td> " . $row['name'] . "</td><td>" . $row['description'] . "</td><td>" . $row['level'] . "</td><td> " . $gender . "</td><td> " . $isUpToUp . "</td><td>" . $row['type'] . "</td></tr>";
                            //echo "<tr><td>" . $row['id'] . " </td><td> " . $row['name'] . "</td><td>" . $row['description'] . "</td><td>" . $row['level'] . "</td><td> " . $gender . "</td><td> " . $isUpToUp . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const searchInput = document.getElementById("searchInput");
                const itemTable = document.getElementById("itemTable");

                searchInput.addEventListener("input", function () {
                    const searchText = searchInput.value.toLowerCase();
                    const rows = itemTable.querySelectorAll("tbody tr");

                    rows.forEach(function (row) {
                        const cells = row.getElementsByTagName("td");
                        let found = false;

                        Array.from(cells).forEach(function (cell) {
                            const cellText = cell.textContent.toLowerCase();

                            if (cellText.includes(searchText)) {
                                found = true;
                            }
                        });

                        if (found) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                    });
                });
            });
        </script>

        <?php
        include 'end.php';
        ?>
