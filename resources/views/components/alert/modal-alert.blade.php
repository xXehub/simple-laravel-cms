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
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const modal = document.getElementById('flashSuccessModal');
                if (modal) {
                    // Show modal dengan animasi fade Tabler.io
                    modal.style.display = 'block';
                    modal.setAttribute('aria-hidden', 'false');

                    // Trigger fade in animation
                    requestAnimationFrame(function() {
                        modal.classList.add('show');
                        document.body.classList.add('modal-open');

                        // Tambahkan backdrop dengan fade
                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade';
                        document.body.appendChild(backdrop);

                        requestAnimationFrame(function() {
                            backdrop.classList.add('show');
                        });

                        // Auto hide setelah 3 detik dengan fade out
                        setTimeout(function() {
                            modal.classList.remove('show');
                            backdrop.classList.remove('show');

                            setTimeout(function() {
                                modal.style.display = 'none';
                                modal.setAttribute('aria-hidden', 'true');
                                document.body.classList.remove('modal-open');
                                if (backdrop.parentNode) {
                                    backdrop.remove();
                                }
                            }, 150); // Tunggu animasi fade selesai
                        }, 3000);

                        // Handle manual close dengan animasi
                        modal.addEventListener('click', function(e) {
                            if (e.target.matches('[data-bs-dismiss="modal"]') || e
                                .target === modal) {
                                modal.classList.remove('show');
                                backdrop.classList.remove('show');

                                setTimeout(function() {
                                    modal.style.display = 'none';
                                    modal.setAttribute('aria-hidden', 'true');
                                    document.body.classList.remove('modal-open');
                                    if (backdrop.parentNode) {
                                        backdrop.remove();
                                    }
                                }, 150);
                            }
                        });
                    });
                }
            }, 200);
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
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const modal = document.getElementById('flashErrorModal');
                if (modal) {
                    // Show modal dengan animasi fade Tabler.io
                    modal.style.display = 'block';
                    modal.setAttribute('aria-hidden', 'false');

                    // Trigger fade in animation
                    requestAnimationFrame(function() {
                        modal.classList.add('show');
                        document.body.classList.add('modal-open');

                        // Tambahkan backdrop dengan fade
                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade';
                        document.body.appendChild(backdrop);

                        requestAnimationFrame(function() {
                            backdrop.classList.add('show');
                        });

                        // Auto hide setelah 4 detik dengan fade out (lebih lama untuk error)
                        setTimeout(function() {
                            modal.classList.remove('show');
                            backdrop.classList.remove('show');

                            setTimeout(function() {
                                modal.style.display = 'none';
                                modal.setAttribute('aria-hidden', 'true');
                                document.body.classList.remove('modal-open');
                                if (backdrop.parentNode) {
                                    backdrop.remove();
                                }
                            }, 150); // Tunggu animasi fade selesai
                        }, 4000);

                        // Handle manual close dengan animasi
                        modal.addEventListener('click', function(e) {
                            if (e.target.matches('[data-bs-dismiss="modal"]') || e
                                .target === modal) {
                                modal.classList.remove('show');
                                backdrop.classList.remove('show');

                                setTimeout(function() {
                                    modal.style.display = 'none';
                                    modal.setAttribute('aria-hidden', 'true');
                                    document.body.classList.remove('modal-open');
                                    if (backdrop.parentNode) {
                                        backdrop.remove();
                                    }
                                }, 150);
                            }
                        });
                    });
                }
            }, 200);
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
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const modal = document.getElementById('flashWarningModal');
                if (modal) {
                    // Show modal dengan animasi fade Tabler.io
                    modal.style.display = 'block';
                    modal.setAttribute('aria-hidden', 'false');

                    // Trigger fade in animation
                    requestAnimationFrame(function() {
                        modal.classList.add('show');
                        document.body.classList.add('modal-open');

                        // Tambahkan backdrop dengan fade
                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade';
                        document.body.appendChild(backdrop);

                        requestAnimationFrame(function() {
                            backdrop.classList.add('show');
                        });

                        // Auto hide setelah 3.5 detik dengan fade out
                        setTimeout(function() {
                            modal.classList.remove('show');
                            backdrop.classList.remove('show');

                            setTimeout(function() {
                                modal.style.display = 'none';
                                modal.setAttribute('aria-hidden', 'true');
                                document.body.classList.remove('modal-open');
                                if (backdrop.parentNode) {
                                    backdrop.remove();
                                }
                            }, 150); // Tunggu animasi fade selesai
                        }, 3500);

                        // Handle manual close dengan animasi
                        modal.addEventListener('click', function(e) {
                            if (e.target.matches('[data-bs-dismiss="modal"]') || e
                                .target === modal) {
                                modal.classList.remove('show');
                                backdrop.classList.remove('show');

                                setTimeout(function() {
                                    modal.style.display = 'none';
                                    modal.setAttribute('aria-hidden', 'true');
                                    document.body.classList.remove('modal-open');
                                    if (backdrop.parentNode) {
                                        backdrop.remove();
                                    }
                                }, 150);
                            }
                        });
                    });
                }
            }, 200);
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
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const modal = document.getElementById('flashInfoModal');
                if (modal) {
                    // Show modal dengan animasi fade Tabler.io
                    modal.style.display = 'block';
                    modal.setAttribute('aria-hidden', 'false');

                    // Trigger fade in animation
                    requestAnimationFrame(function() {
                        modal.classList.add('show');
                        document.body.classList.add('modal-open');

                        // Tambahkan backdrop dengan fade
                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade';
                        document.body.appendChild(backdrop);

                        requestAnimationFrame(function() {
                            backdrop.classList.add('show');
                        });

                        // Auto hide setelah 3 detik dengan fade out
                        setTimeout(function() {
                            modal.classList.remove('show');
                            backdrop.classList.remove('show');

                            setTimeout(function() {
                                modal.style.display = 'none';
                                modal.setAttribute('aria-hidden', 'true');
                                document.body.classList.remove('modal-open');
                                if (backdrop.parentNode) {
                                    backdrop.remove();
                                }
                            }, 150); // Tunggu animasi fade selesai
                        }, 3000);

                        // Handle manual close dengan animasi
                        modal.addEventListener('click', function(e) {
                            if (e.target.matches('[data-bs-dismiss="modal"]') || e
                                .target === modal) {
                                modal.classList.remove('show');
                                backdrop.classList.remove('show');

                                setTimeout(function() {
                                    modal.style.display = 'none';
                                    modal.setAttribute('aria-hidden', 'true');
                                    document.body.classList.remove('modal-open');
                                    if (backdrop.parentNode) {
                                        backdrop.remove();
                                    }
                                }, 150);
                            }
                        });
                    });
                }
            }, 200);
        });
    </script>
@endif

@if (session('restore'))
    <!-- Restore Success Modal -->
    <div class="modal modal-blur fade" id="flashRestoreModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-primary"></div>
                <div class="modal-body text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-primary icon-lg" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 12a8 8 0 0 1 8 -8V0l4 4l-4 4V4a6 6 0 0 0 -6 6H4z" />
                        <path d="M20 12a8 8 0 0 1 -8 8v4l-4 -4l4 -4v4a6 6 0 0 0 6 -6h2z" />
                    </svg>
                    <h3>Data Dipulihkan!</h3>
                    <div class="text-muted">{{ session('restore') }}</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">
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
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const modal = document.getElementById('flashRestoreModal');
                if (modal) {
                    // Show modal dengan animasi fade Tabler.io
                    modal.style.display = 'block';
                    modal.setAttribute('aria-hidden', 'false');

                    // Trigger fade in animation
                    requestAnimationFrame(function() {
                        modal.classList.add('show');
                        document.body.classList.add('modal-open');

                        // Tambahkan backdrop dengan fade
                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade';
                        document.body.appendChild(backdrop);

                        requestAnimationFrame(function() {
                            backdrop.classList.add('show');
                        });

                        // Auto hide setelah 3 detik dengan fade out
                        setTimeout(function() {
                            modal.classList.remove('show');
                            backdrop.classList.remove('show');

                            setTimeout(function() {
                                modal.style.display = 'none';
                                modal.setAttribute('aria-hidden', 'true');
                                document.body.classList.remove('modal-open');
                                if (backdrop.parentNode) {
                                    backdrop.remove();
                                }
                            }, 150); // Tunggu animasi fade selesai
                        }, 3000);

                        // Handle manual close dengan animasi
                        modal.addEventListener('click', function(e) {
                            if (e.target.matches('[data-bs-dismiss="modal"]') || e
                                .target === modal) {
                                modal.classList.remove('show');
                                backdrop.classList.remove('show');

                                setTimeout(function() {
                                    modal.style.display = 'none';
                                    modal.setAttribute('aria-hidden', 'true');
                                    document.body.classList.remove('modal-open');
                                    if (backdrop.parentNode) {
                                        backdrop.remove();
                                    }
                                }, 150);
                            }
                        });
                    });
                }
            }, 200);
        });
    </script>
@endif
{{-- Generic Confirmation Modal --}}
<div class="modal modal-blur fade" id="confirmationModal" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <path d="M12 8v4" />
                    <path d="M12 16h.01" />
                </svg>
                <h3 id="confirmationTitle">Konfirmasi</h3>
                <div class="text-muted" id="confirmationMessage">Apakah Anda yakin?</div>
                <div class="border rounded p-2 bg-light mt-3" id="confirmationDetails" style="display: none;">
                    <strong id="confirmationItemName"></strong><br>
                    <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                </div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">
                                Batal
                            </button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-danger w-100" id="confirmationYesBtn">
                                Ya, Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Restore Confirmation Modal --}}
<div class="modal modal-blur fade" id="restoreConfirmationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-primary"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-primary icon-lg" width="24"
                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 12a8 8 0 0 1 8 -8V0l4 4l-4 4V4a6 6 0 0 0 -6 6H4z" />
                    <path d="M20 12a8 8 0 0 1 -8 8v4l-4 -4l4 -4v4a6 6 0 0 0 6 -6h2z" />
                </svg>
                <h3 id="restoreConfirmationTitle">Pulihkan Data?</h3>
                <div class="text-muted" id="restoreConfirmationMessage">Apakah Anda yakin ingin memulihkan data ini?</div>
                <div class="border rounded p-2 bg-light mt-3" id="restoreConfirmationDetails" style="display: none;">
                    <strong id="restoreConfirmationItemName"></strong><br>
                    <small class="text-muted">Data akan dikembalikan ke status aktif.</small>
                </div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">
                                Batal
                            </button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-primary w-100" id="restoreConfirmationYesBtn">
                                Ya, Pulihkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Global function untuk show confirmation modal dengan animasi Tabler.io
    window.showConfirmationModal = function(options) {
        const modal = document.getElementById('confirmationModal');
        if (!modal) return;

        // Set content
        if (options.title) document.getElementById('confirmationTitle').textContent = options.title;
        if (options.message) document.getElementById('confirmationMessage').textContent = options.message;
        if (options.itemName) {
            document.getElementById('confirmationItemName').textContent = options.itemName;
            document.getElementById('confirmationDetails').style.display = 'block';
        } else {
            document.getElementById('confirmationDetails').style.display = 'none';
        }
        if (options.buttonText) document.getElementById('confirmationYesBtn').textContent = options.buttonText;

        // Show modal dengan animasi fade Tabler.io
        modal.style.display = 'block';
        modal.setAttribute('aria-hidden', 'false');

        // Trigger fade in animation
        requestAnimationFrame(function() {
            modal.classList.add('show');
            document.body.classList.add('modal-open');

            // Tambahkan backdrop dengan fade
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade';
            backdrop.id = 'confirmationBackdrop';
            document.body.appendChild(backdrop);

            requestAnimationFrame(function() {
                backdrop.classList.add('show');
            });

            // Handle close dengan animasi
            const closeModal = function() {
                modal.classList.remove('show');
                backdrop.classList.remove('show');

                setTimeout(function() {
                    modal.style.display = 'none';
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('modal-open');
                    if (backdrop.parentNode) {
                        backdrop.remove();
                    }
                }, 150);
            };

            // Event listeners
            modal.addEventListener('click', function(e) {
                if (e.target.matches('[data-bs-dismiss="modal"]') || e.target === modal) {
                    closeModal();
                }
            });

            // Set callback untuk tombol Yes
            const yesBtn = document.getElementById('confirmationYesBtn');
            yesBtn.onclick = function() {
                if (options.onConfirm) options.onConfirm();
                closeModal();
            };
        });
    };

    // Global function untuk show restore confirmation modal
    window.showRestoreConfirmationModal = function(options) {
        const modal = document.getElementById('restoreConfirmationModal');
        if (!modal) return;

        // Set content
        if (options.title) document.getElementById('restoreConfirmationTitle').textContent = options.title;
        if (options.message) document.getElementById('restoreConfirmationMessage').textContent = options.message;
        if (options.itemName) {
            document.getElementById('restoreConfirmationItemName').textContent = options.itemName;
            document.getElementById('restoreConfirmationDetails').style.display = 'block';
        } else {
            document.getElementById('restoreConfirmationDetails').style.display = 'none';
        }
        if (options.buttonText) document.getElementById('restoreConfirmationYesBtn').textContent = options.buttonText;

        // Show modal dengan animasi fade Tabler.io
        modal.style.display = 'block';
        modal.setAttribute('aria-hidden', 'false');

        // Trigger fade in animation
        requestAnimationFrame(function() {
            modal.classList.add('show');
            document.body.classList.add('modal-open');

            // Tambahkan backdrop dengan fade
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade';
            backdrop.id = 'restoreConfirmationBackdrop';
            document.body.appendChild(backdrop);

            requestAnimationFrame(function() {
                backdrop.classList.add('show');
            });

            // Handle close dengan animasi
            const closeModal = function() {
                modal.classList.remove('show');
                backdrop.classList.remove('show');

                setTimeout(function() {
                    modal.style.display = 'none';
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('modal-open');
                    if (backdrop.parentNode) {
                        backdrop.remove();
                    }
                }, 150);
            };

            // Event listeners
            modal.addEventListener('click', function(e) {
                if (e.target.matches('[data-bs-dismiss="modal"]') || e.target === modal) {
                    closeModal();
                }
            });

            // Set callback untuk tombol Yes
            const yesBtn = document.getElementById('restoreConfirmationYesBtn');
            yesBtn.onclick = function() {
                if (options.onConfirm) options.onConfirm();
                closeModal();
            };
        });
    };
</script>
