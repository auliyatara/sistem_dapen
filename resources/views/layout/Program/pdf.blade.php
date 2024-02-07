<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Resume</title>

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse; /* Collapse border spacing */
            width: 100%; /* 100% width of the table */
        }

        h1{
            text-align: center
        }
        tr, td {
            border: 1px solid #000; /* 1px solid black border */
            padding: 8px; /* Add padding for cell content */
            font-size: 12px;
        }

        ul, ol {
            list-style: none;
            padding: 0; /* Remove padding from lists */
        }

        /* Remove padding from list items */
        li {
            padding: 0;
        }
        
    </style>
</head>
<body>
    <div style="margin: 0 auto;display:">
        <img src="{{ public_path('images/kop_surat.png') }}" style="width:100%;"> 
        <h1>Program Kegiatan</h1>
        <table>
            <thead>
               <tr>
                    <td style="width:5%;text-align: center;">No</td>
                    <td style="width:40%;text-align: center;">Nama Program</td>
                    <td style="text-align: center;">Detail Program</td>
               </tr>
            </thead>
            <tbody>
                @foreach ($data  as $key=>$value)
                    <tr>
                        <td style="width:5%;text-align: center;">{{$key+1}}</td>
                        <td style="width:40%">{{$value['program_nama']}}</td>
                        <td>
                            <ul>
                                @if(count($value['program_pelaksanaan']))
                                    <li><b>Pelaksanaan</b></li>
                                @endif
                                @foreach ($value['program_pelaksanaan']  as $keypelaksanaan=>$valuepelaksanaan)
                                    <li>{{$keypelaksanaan+1}} . {{$valuepelaksanaan['program_pelaksanaan_nama']}}</li>
                                @endforeach

                                @if(count($value['program_pelaksanaan']))
                                    <br>
                                @endif

                                @if(count($value['program_evaluasi']))
                                    <li><b>Evaluasi</b></li>
                                @endif
                                @foreach ($value['program_evaluasi']  as $keyevaluasi=>$valueevaluasi)
                                    <li>{{$keyevaluasi+1}} . {{$valueevaluasi['program_evaluasi_nama']}}</li>
                                @endforeach
                            </ul>
                            
                            

                            
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>