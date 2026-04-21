<!DOCTYPE html>
<html lang="ar">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta charset="UTF-8"/>
    <!-- Add this line to specify UTF-8 encoding -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <title></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@200&display=swap');
        @font-face {
            font-family: Frutiger;
            src: public_path("fonts/FrutigerLTStd-Bold.otf");
        }
        @font-face {
            font-family: 'Frutiger LT Arabic Std';
            src: public_path("fonts/FrutigerLTArabic-65Bold.ttf");
        }

        @font-face {
            font-family: frutiger_arabi;
            src: public_path("fonts/FrutigerLTArabic.ttf");
        }

        /*Add your CSS styles here*/


        * { font-family:'Frutiger LT Std';}
        @media print {
            @font-face {
                font-family: 'Frutiger LT Std';
                src: public_path("fonts/FrutigerLTStd-Bold.otf");
            }
            @font-face {
                font-family: 'Frutiger LT Arabic Std';
                src: public_path("fonts/FrutigerLTArabic-65Bold.ttf");
            }

            @font-face {
                font-family: frutiger_arabi;
                src: public_path("fonts/FrutigerLTArabic.ttf");
            }


            body {
                font-family: Frutiger, sans-serif !important;
            }

            .arabic {
                font-family: frutiger_arabi, sans-serif !important;
            }

        }

        .row {
            width: 100%;
            padding: 1em;
        }

        /* table {
            margin-bottom: 2em;
        } */
    </style>
</head>
<body>
    <table class="row" style="padding-top: 1em;">
        <tbody>
        <tr class="col" style="">
            <td class="" style="width: 40%;">
                <h3 style="margin: 5px 0; color: #12235f;font-size: 1em;">Talent Endorsement Certificate</h3>
                    {{-- <h3 style="margin: 8px 0; color: #89203a;font-size: 1em;">Talent Visa</h3> --}}
            </td>

            <td class="" style="width: 20%; text-align: center;">
                <img src="{{ public_path('images/Jusour-logo-01.svg') }}" alt="" style="width:140px;">
            </td>

            <td class="" style="width: 40%; direction: rtl;">
                <h3 class="arabic" style="margin: 5px 0; color: #12235f; font-family:frutiger_arabi, sans-serif !important;font-size: 1.1em;">شهادة المصادقة على الموهبة</h3>
                    {{-- <h3 class="arabic" style="margin: 8px 0; color: #89203a;font-family: frutiger_arabi, sans-serif !important;font-size: 1.1em;">تأشيرة مواهب </h3> --}}
            </td>
        </tr>
        </tbody>
    </table>

    <table style="width: 100%; justify-content: space-between; padding-top: 6em !important;">
        <tbody>
            <tr style="padding-top: 3em;">
                <td style="padding-top: 1em; border-right: 1px solid #d9d9d9;">
                    <h4 style="padding-bottom: .8rem !important; margin-top: 0; color: #12235f; font-size: 12px;text-align: left;">
                        To whom it may concern,
                    </h4>
                </td>
                <td style="padding-top: 1em; padding-bottom: .8rem !important; margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important; font-size: 12px; direction: rtl;">
                    <h4 class="arabic" style="margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important; font-size: 12px; direction: rtl;">
                        لمن يهمه الأمر،
                    </h4>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;padding: 3em 2em 3em 0;border-right: 1px solid #d9d9d9;
                text-align: left;line-height: 24px;margin-bottom: 0.5rem; color: #12235f;font-size: 12px;min-height: 140px">
                    <div >
                        We are writing to endorse

                        <strong style="border-bottom: 1px dashed #d9d9d9;"> {{$data['applicantNameEn']}}</strong> as a talented
                        professional in the Sector of

                        <strong style="border-bottom: 1px dashed #d9d9d9;">{{$data['sectorEn']}}</strong>. He / She has
                        demonstrated exceptional skills, dedication, and achievements in
                        this domain as per the information and documents provided.
                    </div>
                </td>
                <td style="width: 50%;padding: 3em 0 3em 2em; margin-bottom: 0.5rem;color: #12235f;font-family: frutiger_arabi, sans-serif !important;
                    line-height: 24px; font-size: 12px; direction: rtl; min-height: 140px">
                    <div class="arabic">
                         نفيدكم علما أننا نصادق على موهبة <strong style="border-bottom: 1px dashed #d9d9d9;"> {{$data['applicantNameAr'] ? $data['applicantNameAr'] : $data['applicantNameEn']}} </strong>

                        باعتباره موهوبًا في قطاع  <strong style="border-bottom: 1px dashed #d9d9d9; ">{{ $data['sectorAr'] }}</strong> حيـث أظهـر مهـارات اسـتثنائية وحقـق العديـد مـن الإنجـازات
                        فـي هـذا المجـال وفقً للمرفقـات والمسـتندات التـي تـم
                        تقديمها.

                    </div>
                </td>
            </tr>

            <tr>
                <td style="border-right: 1px solid #d9d9d9;line-height: 24px;">
                    <div style="margin-top: 3em">
                        <div style="margin-bottom: 0; margin-top: 0; color: #12235f;line-height: 18px; font-size: 12px;text-align: left">
                            Entity Name: <strong style="border-bottom: 1px dashed #d9d9d9;width: 75%;">{{$data['entityEn']}}</strong>
                        </div>
                        <div style="margin-bottom: 0; margin-top: 0; color: #12235f;line-height: 18px; font-size: 12px;text-align: left">
                            Date: <strong style="border-bottom: 1px dashed #d9d9d9; width: 88.4%; ">{{$data['currentDate']}}</strong>
                        </div>
                        <div style="margin-bottom: 0; margin-top: 0; color: #12235f;line-height: 18px; font-size: 12px;text-align: left">
                            Applicant's name: <strong style="border-bottom: 1px dashed #d9d9d9; width: 66.3%; ">{{$data['applicantNameEn']}}</strong>
                        </div>
                        @if($data['qid'])
                        <div style="margin-bottom: 0; margin-top: 0; color: #12235f;line-height: 18px; font-size: 12px;text-align: left">
                            QID Number: <strong style="border-bottom: 1px dashed #d9d9d9;  width: 66.5%;">{{$data['qid']}}</strong>
                        </div>
                        @else
                        <div style="margin-bottom: 0; margin-top: 0; color: #12235f;line-height: 18px; font-size: 12px;text-align: left">
                            Passport Number: <strong style="border-bottom: 1px dashed #d9d9d9;  width: 66.5%;">{{$data['passportNumber']}}</strong>
                        </div>
                        @endif
                        <div style="margin-bottom: 0; margin-top: 0; color: #12235f;line-height: 18px; font-size: 12px;text-align: left">
                            NationalityEn: <strong style="border-bottom: 1px dashed #d9d9d9;  width: 77%;">{{$data['nationalityEn']}}</strong>
                        </div>
                        <div style="margin-bottom: 0; margin-top: 0; color: #12235f;line-height: 18px; font-size: 12px;text-align: left">
                            Address: <strong style="border-bottom: 1px dashed #d9d9d9; width: 82.5%;">{{$data['address']}}</strong>
                        </div>
                    </div>
                </td>
                <td style="margin-bottom: 0; margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important;line-height: 24px; font-size: 12px; direction: rtl">
                    <div class="arabic" style="margin-top: 3em">
                        <div class="arabic" style="margin-bottom: 0; margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important;line-height: 18px; font-size: 12px; direction: rtl">
                            اسم الجهة:  <strong style="border-bottom: 1px dashed #d9d9d9;display: inline-block; width: 79%; ">{{ $data['entityAr'] }}</strong>
                        </div>
                        <div class="arabic" style="margin-bottom: 0; margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important;line-height: 18px; font-size: 12px; direction: rtl">
                            التاريخ:  <strong style="border-bottom: 1px dashed #d9d9d9;width: 87%; ">
                                                {{ $data['currentDate'] }}
                                        </strong>
                        </div>
                        <div class="arabic" style="margin-bottom: 0; margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important;line-height: 18px; font-size: 12px; direction: rtl">
                            اسم مقدم الطلب:  <strong style="border-bottom: 1px dashed #d9d9d9; width: 68%; ">{{$data['applicantNameAr'] ? $data['applicantNameAr'] : $data['applicantNameEn']}}</strong>
                        </div>
                        @if($data['qid'])
                        <div class="arabic" style="margin-bottom: 0; margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important;line-height: 18px; font-size: 12px; direction: rtl">
                            الرقم الشخصي:  <strong style="border-bottom: 1px dashed #d9d9d9; width: 72%;">
                                {{$data['qid']}}
                                                </strong>
                        </div>
                        @else
                        <div class="arabic" style="margin-bottom: 0; margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important;line-height: 18px; font-size: 12px; direction: rtl">
                            رقم جواز السفر:  <strong style="border-bottom: 1px dashed #d9d9d9; width: 72%;">
                                {{$data['passportNumber']}}
                                                </strong>
                        </div>
                        @endif
                        <div class="arabic" style="margin-bottom: 0; margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important;line-height: 18px; font-size: 12px; direction: rtl">
                            الجنسية:  <strong style="border-bottom: 1px dashed #d9d9d9; width: 84%;">{{ $data['nationalityAr'] ? $data['nationalityAr'] : $data['nationalityEn'] }}</strong>
                        </div>
                        <div class="arabic" style="margin-bottom: 0; margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important;line-height: 18px; font-size: 12px; direction: rtl">
                            العنوان:  <strong style="border-bottom: 1px dashed #d9d9d9;  width: 85%;">{{$data['address']}}</strong>
                        </div>
                    </div>
                </td>
            </tr>

            <tr style="padding-top: 2em;">
                <td style="border-right: 1px solid #d9d9d9;padding-top: 2em; padding-bottom: 2em">
                    <div style="">
                        <p style="margin-bottom: 0.5rem; margin-top: 0; color: #12235f;line-height: 26px; font-size: 12px;text-align: left">
                            Sincerely,
                        </p>
                    </div>
                </td>
                <td style="margin-bottom: 0.5rem; padding-top: 2em; padding-bottom: 2em; color: #12235f;font-family: frutiger_arabi, sans-serif !important;line-height: 26px; font-size: 12px; direction: rtl">
                    <div style="">
                        <p class="arabic" style="margin-bottom: 0.5rem; margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important;line-height: 26px; font-size: 12px; direction: rtl">
                            مع خالص الشكر والتقدير،
                        </p>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="row" style="padding: 0.5em 0 0.5em 0;">
        <tbody>
            <tr class="col">
                <td class="" style="width: 33.33%;margin-bottom: 0.5rem; margin-top: 0; color: #12235f;line-height: 26px; font-size: 12px;text-align: left">
                    <p style="">
                        Endorsement Code:*
                    </p>
                </td>

                <td class="" style="width: 33.33%; text-align: center;border: 1px solid #d9d9d9;padding: 2em;">
                    <h4
                        class="arabic_heading"
                        style="margin-bottom: 0.5rem; margin-top: 0"
                    >
                        <strong>{{$data['secureCode']}}</strong>

                    </h4>
                </td>

                <td class="arabic" style="width: 33.33%; direction: rtl;
                 margin-bottom: .5rem; margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important; font-size: 12px;">
                    <p class="arabic">
                        *رقم المصادقة:
                    </p>
                </td>
            </tr>
        </tbody>
    </table>


    <table style="width: 100%; justify-content: space-between; padding-top: 8em !important;">
        <tbody>
            <tr style="padding-top: 3em;">
                <td style=" width: 50%;padding-top: 1em; border-right: 1px solid #d9d9d9;">
                    <h4 style="padding-bottom: .8rem !important; margin-top: 0; color: #12235f; font-size: 9px;text-align: left;">
                        *The above endorsement code is valid for 6 months from issuance
                    </h4>
                </td>
                <td style=" width: 50%;padding-top: 1em; padding-bottom: .8rem !important; margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important; font-size: 12px; direction: rtl;">
                    <h4 class="arabic" style="margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important; font-size: 12px; direction: rtl;">
                        *رقم المصادقة أعلاه صالح لمدة 6 أشهر من تاريخ صدوره
                    </h4>
                </td>
            </tr>
            <tr style="padding-top: 3em;">
                <td style=" width: 50%;padding-top: 1em; border-right: 1px solid #d9d9d9;">
                    <h3 style="padding-bottom: 0 !important; margin-top: 0; color: #12235f; font-size: 9px;text-align: left;"> Disclaimer:</h3>
                    <h4 style="padding-bottom: .8rem !important; margin-top: 0; color: #12235f; font-size: 9px;text-align: left;">
                        This endorsement doesn’t guarantee the approval for the issuance of the visa/residency.
Candidate to be aware that there are other required documents to be submitted for the visa/residency application to be considered. The Talent visa or residency is not granted. The issuance of the Talent visa itself does not guarantee the issuance of a residency permit as the candidate would still have to pass the medical exam and fulfil all requirements to be issued a residency permit once in Qatar.
The issuance of the visa and subsequently of a residency permit are subject to the approval of the related government entities.
The endorser entity holds the right to cancel the endorsement in their discretion without giving any reasons. This endorsement letter’s purpose is to be used solely for the application of the Talent visa or residency in the Talent category.

                    </h4>
                </td>
                <td style=" width: 50%;padding-top: 1em; padding-bottom: .8rem !important; margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important; font-size: 12px; direction: rtl;">
                    <h3 class="arabic" style="margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important; font-size: 12px; direction: rtl;">                    تنويه: </h3>
                    <h4 class="arabic" style="margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important; font-size: 12px; direction: rtl;">
                        هذه المصادقة لا تضمن الحصول على الموافقة على الإقامة .يجب على المرشح أن يدرك أن هناك مستندات أخرى مطلوبة لتقديمها للحصول على الإقامة حتى يتم النظر في الطلب.
                        لا تتحمل الجهة المصادقة المسؤولية في حالة عدم منح التأشيرة او الإقامة
                        اصدار التأشيرة خاضعة لموافقة الجهات الحكومية ذات الصلة.
                        للجهة المصادقة الحق في إلغاء المصادقة على الموهبة حسبما تراه مناسبا.
                        تُستخدم خطابات المصادقة على الموهبة هذه فقط لتطبيق مبادرة إقامة مواهب.

                    </h4>
                </td>
            </tr>
        </tbody>
    </table>

    <footer style="border-top: 1px solid #d9d9d9; text-align: center;padding: 1em 0 0;
     position: absolute; bottom: 0;left: 0; width: 100%;">
        <p style="color: #89203a;line-height: 26px; font-size: 12px; margin: 0;">
            www.jusour.qa  <span class="arabic" style="margin-bottom: .8rem; margin-top: 0; color: #12235f;font-family: frutiger_arabi, sans-serif !important; font-size: 12px; direction: rtl;">لمزيد من المعلومات يرجى زيارة موقعنا الدلكتروني   </span>
        </p>

        <p style="color: #12235f;line-height: 26px; font-size: 12px; margin: 0;">
            For more information, please visit our website <span style="color: #89203a;"> www.jusour.qa </span>
        </p>

        <p style="color: #12235f;line-height: 26px; font-size: 12px; margin: 0;">
            © copyright <span style="color: #89203a;">Jusour  </span>{{ date('Y') }}.
        </p>


        <img src="{{ public_path('images/linePng.png') }}" alt="" style="padding-top: 1em">
    </footer>
</body>
</html>
