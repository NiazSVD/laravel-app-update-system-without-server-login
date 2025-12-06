 <script src="{{ asset('backend/assets/vendors/jquery/jquery.min.js') }}"></script>
 <script src="https://cdn.jsdelivr.net/npm/mixitup@3.3.1/dist/mixitup.min.js"></script>
 <script src="{{ asset('backend/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
 <script src="{{ asset('backend/assets/vendors/simple-datatables/simple-datatables.js') }}"></script>
 <script src="{{ asset('backend/assets/js/bootstrap.bundle.min.js') }}"></script>
 <script src="{{ asset('backend/assets/vendors/choices.js/choices.min.js') }}"></script>
 <script src="{{ asset('backend/assets/vendors/toastify/toastify.js') }}"></script>
 <script src="{{ asset('backend/assets/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>
 <script src="{{ asset('backend/assets/js/main.js') }}"></script>


 <script>
     @if (session('error'))
         Toastify({
             text: "{{ session('error') }}",
             duration: 5000,
             close: true,
             gravity: "top",
             position: "right",
             backgroundColor: "#e74c3c",
         }).showToast();
     @endif

     @if ($errors->any())
         @foreach ($errors->all() as $error)
             Toastify({
                 text: "{{ $error }}",
                 duration: 5000,
                 close: true,
                 gravity: "top",
                 position: "right",
                 backgroundColor: "#e74c3c",
             }).showToast();
         @endforeach
     @endif

     @if (session('success'))
         Toastify({
             text: "{{ session('success') }}",
             duration: 5000,
             close: true,
             gravity: "top",
             position: "right",
             backgroundColor: "#4fbe87",
         }).showToast();
     @endif
 </script>



 @yield('script')
