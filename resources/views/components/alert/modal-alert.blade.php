{{-- Flash Messages Modal Tabler.io --}}
@if (session('success'))
    <!-- Success Modal -->
    <div class="modal modal-blur fade" id="flashSuccessModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-success"></div>
                <div class="modal-body text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-success icon-lg" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M9 12l2 2l4 -4" />
                    </svg>
                    <h3>Berhasil!</h3>
                    <div class="text-muted">{{ session('success') }}</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-success w-100" data-bs-dismiss="modal">
                                    OK
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Tunggu sampai semua script dimuat
        window.addEventListener('load', function() {
            setTimeout(function() {
                var modal = document.getElementById('flashSuccessModal');
                if (modal) {
                    // Gunakan vanilla JS untuk show modal
                    modal.classList.add('show');
                    modal.style.display = 'block';
                    document.body.classList.add('modal-open');

                    // Tambahkan backdrop
                    var backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);

                    // Handle close
                    modal.addEventListener('click', function(e) {
                        if (e.target.matches('[data-bs-dismiss="modal"]') || e.target === modal) {
                            modal.classList.remove('show');
                            modal.style.display = 'none';
                            document.body.classList.remove('modal-open');
                            backdrop.remove();
                        }
                    });
                }
            }, 100);
        });
    </script>
@endif

@if (session('error'))
    <!-- Error Modal -->
    <div class="modal modal-blur fade" id="flashErrorModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M10 10l4 4m0 -4l-4 4" />
                    </svg>
                    <h3>Error!</h3>
                    <div class="text-muted">{{ session('error') }}</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-danger w-100" data-bs-dismiss="modal">
                                    OK
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Tunggu sampai semua script dimuat
        window.addEventListener('load', function() {
            setTimeout(function() {
                var modal = document.getElementById('flashErrorModal');
                if (modal) {
                    // Gunakan vanilla JS untuk show modal
                    modal.classList.add('show');
                    modal.style.display = 'block';
                    document.body.classList.add('modal-open');

                    // Tambahkan backdrop
                    var backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);

                    // Handle close
                    modal.addEventListener('click', function(e) {
                        if (e.target.matches('[data-bs-dismiss="modal"]') || e.target === modal) {
                            modal.classList.remove('show');
                            modal.style.display = 'none';
                            document.body.classList.remove('modal-open');
                            backdrop.remove();
                        }
                    });
                }
            }, 100);
        });
    </script>
@endif

@if (session('warning'))
    <!-- Warning Modal -->
    <div class="modal modal-blur fade" id="flashWarningModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-warning"></div>
                <div class="modal-body text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-warning icon-lg" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M12 8v4" />
                        <path d="M12 16h.01" />
                    </svg>
                    <h3>Peringatan!</h3>
                    <div class="text-muted">{{ session('warning') }}</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-warning w-100" data-bs-dismiss="modal">
                                    OK
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Tunggu sampai semua script dimuat
        window.addEventListener('load', function() {
            setTimeout(function() {
                var modal = document.getElementById('flashWarningModal');
                if (modal) {
                    // Gunakan vanilla JS untuk show modal
                    modal.classList.add('show');
                    modal.style.display = 'block';
                    document.body.classList.add('modal-open');

                    // Tambahkan backdrop
                    var backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);

                    // Handle close
                    modal.addEventListener('click', function(e) {
                        if (e.target.matches('[data-bs-dismiss="modal"]') || e.target === modal) {
                            modal.classList.remove('show');
                            modal.style.display = 'none';
                            document.body.classList.remove('modal-open');
                            backdrop.remove();
                        }
                    });
                }
            }, 100);
        });
    </script>
@endif

@if (session('info'))
    <!-- Info Modal -->
    <div class="modal modal-blur fade" id="flashInfoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-info"></div>
                <div class="modal-body text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-info icon-lg" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M12 8h.01" />
                        <path d="M11 12h1v4h1" />
                    </svg>
                    <h3>Informasi</h3>
                    <div class="text-muted">{{ session('info') }}</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-info w-100" data-bs-dismiss="modal">
                                    OK
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Tunggu sampai semua script dimuat
        window.addEventListener('load', function() {
            setTimeout(function() {
                var modal = document.getElementById('flashInfoModal');
                if (modal) {
                    // Gunakan vanilla JS untuk show modal
                    modal.classList.add('show');
                    modal.style.display = 'block';
                    document.body.classList.add('modal-open');

                    // Tambahkan backdrop
                    var backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);

                    // Handle close
                    modal.addEventListener('click', function(e) {
                        if (e.target.matches('[data-bs-dismiss="modal"]') || e.target === modal) {
                            modal.classList.remove('show');
                            modal.style.display = 'none';
                            document.body.classList.remove('modal-open');
                            backdrop.remove();
                        }
                    });
                }
            }, 100);
        });
    </script>
@endif
