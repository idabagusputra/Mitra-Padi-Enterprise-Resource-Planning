  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <footer class="footer py-5">
      <div class="container">
          <div class="row">
              <!-- <div class="col-lg-8 mb-4 mx-auto text-center">
          <a href="https://www.creative-tim.com/?_ga=2.242299972.757293697.1638911086-1528502635.1638911086" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Company
          </a>
          <a href="https://www.creative-tim.com/presentation" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              About Us
          </a>
          <a href="https://www.creative-tim.com/presentation" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Team
          </a>
          <a href="https://www.creative-tim.com/templates" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Products
          </a>
          <a href="https://www.creative-tim.com/blog" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Blog
          </a>
          <a href="https://www.creative-tim.com/support-terms" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Pricing
          </a>
      </div> -->
              @if (!auth()->user() || \Request::is('static-sign-up'))
              <div class="col-lg-8 mx-auto text-center mb-4 mt-2">
                  <!-- <a class="text-lg me-xl-4 me-4">
                      <span class="text-lg">    conttact me </span>
                  </a> -->
                  <a href="https://wa.me/6282260773867" target="_blank" class="text-secondary me-xl-4 me-4">
                      <span class="text-lg fab fa-whatsapp" aria-hidden="true"></span>
                  </a>
                  <!-- <a href="https://twitter.com/CreativeTim" target="_blank" class="text-secondary me-xl-4 me-4">
                      <span class="text-lg fab fa-twitter" aria-hidden="true"></span>
                  </a> -->
                  <a href="https://www.instagram.com/ida.bagus.putra" target="_blank" class="text-secondary me-xl-4 me-4">
                      <span class="text-lg fab fa-instagram" aria-hidden="true"></span>
                  </a>
                  <a href="https://www.linkedin.com/in/ida-bagus-putu-putra-manuaba/" target="_blank" class="text-secondary me-xl-4 me-4">
                      <span class="text-lg fab fa-linkedin" aria-hidden="true"></span>
                  </a>
                  <a href="https://github.com/idabagusputra" target="_blank" class="text-secondary me-xl-4 me-4">
                      <span class="text-lg fab fa-github" aria-hidden="true"></span>
                  </a>
              </div>
              @endif
          </div>
          @if (!auth()->user() || \Request::is('static-sign-up'))
          <div class="row">
              <div class=" mx-auto text-center mt-1 style="text-align: justify; width: 100%;">
                  <p class="mb-0 text-secondary">
                      Mitra Padi, a tailored ERP system for UD Penggilingan Padi Putra Manuaba. Built to optimize rice milling operations, this ERP enhances data management, credit tracking, and transaction workflows, improving accuracy, transparency, and efficiency<script>
                          //   document.write(new Date().getFullYear())
                      </script>, made by -
                      <a style="color: #252f40;" href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Ida Bagus Putu Putra Manuaba</a>
                      <!-- & -->
                      <!-- <a style="color: #252f40;" href="https://www.updivision.com" class="font-weight-bold ml-1" target="_blank">UPDIVISION</a>. -->
                  </p>
              </div>
          </div>
          @endif
      </div>
  </footer>
  <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->