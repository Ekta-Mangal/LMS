<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Certificate</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet" />
</head>

<body
    style="overflow: hidden; position: relative; margin: 0; padding: 0; box-sizing: border-box; font-family: 'Open Sans', serif; font-optical-sizing: auto;">

    <div class="certificate"
        style="width: 100%; height: 100vh; background-color: #ffffff; display: flex; align-items: center; justify-content: center;">

        <div class="certificate_structure"
            style="height: 85%; width: 55%; max-width: 100%; background-color: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; position: relative;">

            <img src="{{ public_path('certificate/images/bg-top-left.png') }}" alt="" class="img_top_left"
                style=" height: 7rem; width: 7rem; position: absolute; top: 0; left: 0;" />

            <img src="{{ public_path('certificate/images/bg-top-center.png') }}" alt="" class="img_top_center"
                style="height: 2.2rem; width: 35rem; position: absolute; top: 0" />

            <img src="{{ public_path('certificate/images/bg-top-right.png') }}" alt="" class="img_top_right"
                style="height: 7rem; width: 7rem; position: absolute; top: 0; right: 0;" />

            <div class="certificate_content"
                style="height: 70%; width: 70%; background-color: #ffffff; border: 4px solid rgb(217, 164, 0); border-bottom: none; display: flex; flex-direction: column; align-items: center; justify-content: space-around; padding: 2rem; position: relative;">

                <div class="logo">
                    <img src="{{ public_path('certificate/images/Group 308 (1).svg') }}" alt=""
                        style="height: 4rem; width: 12rem; margin-top: -1em;" />
                </div>

                <div class="certificate_heading_img"
                    style="display: flex; align-items: center; justify-content: flex-start; gap: 3rem; margin-top: -1rem;">

                    <div class="certificate_img">
                        <img src="{{ public_path('certificate/images/Silver.png') }}" alt=""
                            style="height: 6em; width: 5em">
                    </div>

                    <div class="certificate_heading">
                        <h1
                            style="font-size: 2rem; color: rgb(6, 9, 52); text-align: center; margin-top: rem; margin-right: 7.6rem; margin-bottom: -.1em;">
                            CERTIFICATE
                        </h1>
                        <h5 style="font-size: 1.3rem; margin-left: 1.1rem; margin-top: -0.2rem; color: rgb(6, 9, 52);">
                            of Achievement</h5>
                    </div>
                </div>

                <div class="paragraph" style="text-align: center">
                    <p style="font-size: 1rem; color: rgba(15, 18, 63, 1); margin-top: -0.5rem;">This Certificate is
                        Proudly Presented To</p>

                    <p class="employee_name"
                        style="margin-top: -0.5rem; font-size: 2.5rem; font-family: 'Great Vibes', cursive; font-weight: 600;">
                        Employee name</p>

                    <div class="employee_line"
                        style="height: 1.2px; width: 11rem; background-color: #000; margin: 0 auto; margin-top: -1.5rem;">
                    </div>
                </div>

                <div class="topic_intro" style="text-align: center">
                    <p style="font-size: 0.8rem; color: rgba(15, 18, 63, 1); margin-bottom: 0.8rem; line-height: 1.3;">

                        <span class="first">
                            WHO HAVE COMPLETED A QMS [Course Name] [Gold , Platinum , Silver]
                        </span>
                        <br />
                        <span class="second">
                            TRAINING PROGRAM, INDICATING THAT THEY HAVE MET THE REQUIRED
                        </span>
                        <br />
                        <span class="third">STANDARDS OF KNOWLEDGE AND SKILLS</span>
                    </p>
                </div>

                <div class="footer_certificate" style="color: #1a0e4b; text-align: center">
                    <div class="footer_line"
                        style="height: 1.5px; width: 6rem; background-color: #000; margin: 0 auto; margin-bottom: 0.5em;">
                    </div>
                    <h4 style="font-size: 0.9rem; margin-top: -0.1rem">
                        <span>QMS Learning HUB</span> <br />
                        COGENT E-SERVICES LTD.
                    </h4>
                </div>
            </div>
            <img src="{{ public_path('certificate/images/bg-bottom.png') }}" alt="" class="img_footerbg"
                style="position: absolute; bottom: 0; width: 100%; height: 10rem; object-fit: cover;" />
        </div>
    </div>
</body>

</html>
