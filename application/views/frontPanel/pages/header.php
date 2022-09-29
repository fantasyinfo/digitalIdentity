<!doctype html>
<html lang="en">
  <head>

    <title><?= $data['pageTitle'];?></title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

        <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
        


    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            background: #e1e8f0;
            font-family: 'Poppins', sans-serif;
            /* display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh; */
            color: #000;
        }

        .resumebox {
            width: 1100px;
            margin: 50px auto;
        }

        .whitebox {
            background: #fff;
            box-shadow: rgba(136, 165, 191, 0.48) 6px 2px 16px 0px, rgba(255, 255, 255, 0.8) -6px -2px 16px 0px;
            display: block;
            border-radius: 8px;
        }

        .namebox {
            padding: 30px;
        }

        .imgboy {
            width: 114px;
            height: 114px;
            overflow: hidden;
            border: 3px solid #ddd;
            margin-right: 30px;
        }

        .imgboy,
        .imgboy>img {
            border-radius: 50%;
        }

        .img-res {
            max-width: 100%;
            max-height: 100%;
        }

        .name_box>h1 {
            font-size: 30px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .name_box>span {
            color: #7b7b7b;
            font-size: 16px;
        }

        .qrbox>h2 {
            background: #eee;
            border-radius: 8px;
            font-size: 19px;
            padding: 15px;
        }

        .qrbox>h2 {
            display: block;
            text-align: center;
        }

        .qrbox>span {
            display: block;
            text-align: center;
            margin: 15px;
        }

        .qrbox>span>h6 {
            color: #7b7b7b;
            font-size: 16px;
        }

        .btnbox {
            column-gap: 10px;
            padding-bottom: 20px;
        }

        .btnbox>a {
            padding: 10px 21px;
            display: block;
            border-radius: 10px;
            font-size: 15px;
            text-decoration: none;
            color: #fff;
        }

        .btnbox>a>span {
            margin-left: 10px;
        }

        .btnbox>.btn_p {
            background: #b72d46;
        }

        .btnbox>.btn_s {
            background: #31b25f;
        }

        .btnbox>.btn_d {
            background: #8247fb;
        }

        .school_dts {
            padding: 40px 30px;
        }

        .detailbox>ul {
            margin: 0;
            padding: 0;
        }

        .detailbox>ul>li {
            list-style: none;
            margin-bottom: 25px;
        }

        .detailbox>ul>li:last-child {
            margin-bottom: 0;
        }

        .detailbox>ul>li>label {
            color: #7b7b7b;
            font-size: 15px;
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .box_dts>span {
            display: flex;
            width: 35px;
            height: 35px;
            align-items: center;
            justify-content: center;
        }

        .box_dts>h4 {
            margin-bottom: 0;
            margin-left: 10px;
            font-size: 19px;
            font-weight: 500;
            color: #303030;
            width: 90%;
        }
        .rv_head {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
        }

        .rv_head>h3 {
            font-size: 20px;
            font-weight: 600;
        }

        .ftrbox>span {
            font-weight: 400;
            font-size: 14px;
        }

        .ftrbox>select {
            border: 1px solid #ddd;
            padding: 8px 10px;
            font-size: 14px;
            border-radius: 5px;
        }
       .pdvb{padding: 20px 20px;}
        .rvdetails>span{font-size: 13px;}
        .star{padding: 10px 0px; column-gap: 5px;}
        .star>span{display: block; width: 20px;}
        .star>h5{    margin-bottom: 0;
    font-size: 15px;
    margin-top: 5px;
    margin-left: 5px;}
    .sttxt>h5{    font-size: 15px;
    margin-bottom: 0;}
   .details_rv>h4 {
    font-size: 17px;}
    .details_rv>p{font-size: 14px;}
    .btnmr{border: none; background: none; font-size: 14px; color: rgb(64, 174, 238); margin-left: 5px;}
    .details_rv{margin-top: 20px;}
    .lidsr{list-style: none; padding: 0; margin: 0; column-gap: 30px; flex-wrap: wrap;}
    .lidsr>li{position: relative; }
    .lidsr>li::after{    position: absolute;
    right: -14px;
    width: 5px;
    height: 5px;
    background: #000;
    content: '';
    top: 10px; border-radius: 50%;} 
    .lidsr>li>a{font-size: 14px; display: block; text-decoration: none;}
    .boxname_d>h5,.boxname_d>h6{margin-bottom: 0;}
    .boxname_d>h5{    border: 1px solid rgb(64, 174, 238);
        color:  rgb(64, 174, 238);
    padding: 5px 10px;
    margin-right: 10px;}
    .boxname_d>h6,.boxname_d>span{    font-size: 13px;
    color: #c1c1c1;
    font-weight: 400;}
    .boxname_d>h6>b{color: #000;}
    .whitebox{margin-bottom: 20px;}
    #qr2{display: none;}

        @media screen and (max-width: 1100px) {
            .resumebox {
                width: 100%;
                margin: 50px auto;
            }
        }

        @media screen and (max-width: 990px) {
            #qr2{display: block;}
            #qr1{display: none;}
            .resumebox .container {
                max-width: 100%;
            }

            .namebox {
                padding: 20px;
            }

            .resumebox {
                margin: 10px auto;
            }

            .name_box>h1 {
                font-size: 20px;
            }

            .name_box>span {
                font-size: 14px;
            }

            .imgboy {
                margin-right: 20px;
            }

            .btnbox>a {
                padding: 10px 12px;
                font-size: 14px;
            }

            .btnbox {
                column-gap: 5px;
            }

            .school_dts {
                padding: 20px 20px;
            }

            .detailbox>ul>li>label {
                font-size: 14px;
            }

            .box_dts>h4 {
                font-size: 16px;
            }
            @media screen and (max-width: 480px) {
                .rv_head>h3{width: 100%;}
                .rv_head{flex-wrap: wrap;}
            }

            @media screen and (min-width: 220px)and (max-width: 320px) {
                .namebox {
                    flex-wrap: wrap;
                    justify-content: center;
                }

                .name_box {
                    text-align: center;
                    margin-top: 10px;
                }

                .btnbox>a>span {
                    margin-left: 0;
                    display: block;
                    text-align: center;
                }

                .detailbox>ul>li>label {
                    font-size: 12px;
                }

                .box_dts>h4 {
                    font-size: 13px;
                }
            }
        }


        
    </style>








   




 
