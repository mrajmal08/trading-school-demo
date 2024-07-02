<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Trading School</title>
   <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
   <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
   <link rel="stylesheet" href="{{ asset('assets/css/apexcharts.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
   <style>
      .select2-container--default .select2-selection--multiple {
         background-color: #1d1f25;
         border: 1px solid #5d6588;
         min-height: 41px;
         border-radius: 4px;
         cursor: text;
         padding-bottom: 5px;
         padding-right: 5px;
         position: relative;
      }

      .select2-results {
         background-color: #1d1f25;
      }

      .select2-container--default .select2-selection--multiple .select2-selection__choice {
         background-color: #1d1f25;
      }

      .select2-results__option[aria-selected=true] {
         color: #ffffff;
         background-color: #9f998d;
      }

      .select2-container--default .select2-results__option--selected {
         background-color: #403bec;
      }
   </style>
</head>

<body>
   <div class="dashboard-layout">
      <!-- Header Start -->
      @include('layout.sidebar')
      <!-- Header End -->

      <main class="content-wrapper matrix-wrapper">
         <!-- Header Start -->
         @include('layout.header')
         <!-- Header End -->

         <section class="content">
            @yield('app-content')
         </section>
      </main>
   </div>

   <!-- jQuery 1.7.2+ or Zepto.js 1.0+ -->
   {{-- <script src="https://code.jquery.com/jquery-3.6.1.min.js"
      integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script> --}}
   <script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
      <!-- Main JS -->
   <script src="{{ asset('assets/js/app.js') }}"></script>
   <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
   <script type="text/javascript" src="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
   @stack('matrix-script')
   @stack('toastr-script')
   @stack('scripts')
   @stack('remove_teacher_script');
   <script>
      const formatNumber = (arg, decimal = 2) => {
         const param = typeof arg === 'string' ? parseFloat(arg).toFixed(decimal) : `${arg}`;
         return parseFloat(parseFloat(param).toFixed(decimal)).toLocaleString('en-US', {
            useGrouping: true,
            minimumFractionDigits: decimal,
         });
      };

      const dollarFormat = (amount, _scale = '') => {
         if (_scale) {
            console.log('SCALE', _scale, _scale.split('.')[1].length);
            const decimalLength = _scale.split('.')[1].length;
            return `$${formatNumber(amount, decimalLength)}`;
         }
         return `$${formatNumber(amount)}`;
      };
   </script>
   <script>
      $(document).ready(function(){
         $('.hamburger').on("click", function(e){
            console.log("Hello World");
            $('.sidebar-wrapper').addClass('show-sidebar');
         });

         $('input[name="dates"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
               cancelLabel: 'Clear'
            }
         });

         $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
               $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
         });

         $('input[name="dates"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
         });

         $('#multiple_user').select2();

         $('.hamburger').on("click", function(e){
            e.preventDefault();
            $('.sidebar-wrapper').addClass('show-sidebar');
         });
      });

      @if (Session::has('success'))
         toastr.success("{{ Session::get('success') }}", "", {
            positionClass: 'toast-bottom-right'
         });
      @endif
      @if (Session::has('error'))
         toastr.error("{{ Session::get('error') }}", "", {
            positionClass: 'toast-bottom-right'
         });
      @endif

      @if (Session::has('status'))
         var type = "{{ Session::get('status') }}";
         switch (type) {
            case 'success':
               toastr.success("{{ Session::get('message') }}");
               break;
            case 'error':
               toastr.error("{{ Session::get('message') }}");
               break;
            case 'info':
               toastr.info("{{ Session::get('message') }}");
               break;

            case 'warning':
               toastr.warning("{{ Session::get('message') }}");
               break;
         }
      @endif
   </script>

   </script>
</body>

</html>
