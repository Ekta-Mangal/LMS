<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=1123, height=794, initial-scale=1.0" />
    <title>certificate-96dpi</title>
</head>

<body
    style="
      margin: 0;
      padding: 0;
      width: 1123px !important;
      height: 796px !important;
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      margin-top: -3rem;
      margin-left:-2rem;
    ">

    <img src="{{ public_path('certificate/images/cert2.svg') }}" alt="Certificate background"
        style="
        position: absolute;
        top: 0;
        left: 0;
        width: 1123px !important;
        height: 796px !important;
        object-fit: cover;
        page-break-inside: avoid;
      " />

    <div
        style="
        position: absolute;
        top: 40%;
        left: 50%;
        transform: translateX(-50%);
        text-align: center;
        width: 80%;
      ">
        <p
            style="
          font-size: 40px;
          margin: 25px 0px 5px;
          font-weight: 600;
          letter-spacing: 2px;
        ">
            {{ $employee_name }}
        </p>
        <p
            style="
          font-size: 24px;
          font-family: 'Poppins', sans-serif;
          line-height: 1.6;
          color: #000000;
          font-weight: 500;
          width: 100%;
        ">
            You have successfully completed our {{ $course_name }} Training Program
        </p>

        <div class="certificate_footer" style="     
          text-align:center;
          width: 100%;
        ">
            <div class="issued_date" style="position: absolute;left:-2%; top:38%;">
                <div class="label" style="font-size: 16px; margin-bottom: 4px;">
                    Issue Date:
                </div>
                <div class="dotted-line"
                    style="
              border-bottom: 2px dotted #888;
              width: 150px;
              margin-bottom: 6px;
            ">
                </div>
                <div class="value" style="font-size: 18px; font-weight: bold">
                    {{ $issue_date }}
                </div>
            </div>

            <div class="badge_img">
                <img src="{{ public_path('certificate/images/' . $badge_level . '.png') }}"
                    style="height: 9rem; width: 7rem; position: absolute; left: 40%; top: 33%;"
                    alt="{{ $badge_level }} Badge" />
            </div>

            <div class="signature">
                <img src="{{ public_path('certificate/images/sign.png') }}" style="position: absolute; right:0; top:33%"
                    alt="" />
            </div>
        </div>
    </div>

</body>

</html>
