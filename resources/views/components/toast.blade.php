@if (session('success') || session('error') || session('warning') || session('info'))

    <style>
        .toast-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: #ffffff;
            border-radius: 12px;
            padding: 14px 16px;
            min-width: 280px;
            max-width: 340px;
            position: relative;
            overflow: hidden;
            opacity: 0;
            transform: translateX(40px) scale(0.95);
            transition: opacity 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .toast-item.show {
            opacity: 1;
            transform: translateX(0) scale(1);
        }

        .toast-item.hide {
            opacity: 0;
            transform: translateX(40px) scale(0.95);
        }

        .toast-icon-badge {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .toast-icon-badge svg {
            width: 16px;
            height: 16px;
        }

        .toast-body {
            flex: 1;
            padding-top: 2px;
        }

        .toast-title {
            font-size: 13px;
            font-weight: 700;
            margin: 0 0 3px 0;
            color: #1e1e2d;
        }

        .toast-msg {
            font-size: 12.5px;
            margin: 0;
            color: #71748a;
            line-height: 1.45;
        }

        .toast-close {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            line-height: 1;
            padding: 0;
            color: #c2c4d4;
            margin-top: -2px;
            transition: color 0.2s;
            flex-shrink: 0;
        }

        .toast-close:hover {
            color: #71748a;
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 100%;
            transform-origin: left;
            animation: toast-shrink 4s linear forwards;
        }

        @keyframes toast-shrink {
            from {
                transform: scaleX(1);
            }

            to {
                transform: scaleX(0);
            }
        }

        /* success */
        .toast-success .toast-icon-badge {
            background: #e7f9ef;
        }

        .toast-success .toast-progress {
            background: linear-gradient(90deg, #22c55e, #16a34a);
        }

        /* error */
        .toast-error .toast-icon-badge {
            background: #fde8e8;
        }

        .toast-error .toast-progress {
            background: linear-gradient(90deg, #ef4444, #dc2626);
        }

        /* warning */
        .toast-warning .toast-icon-badge {
            background: #fef3e0;
        }

        .toast-warning .toast-progress {
            background: linear-gradient(90deg, #f59e0b, #d97706);
        }

        /* info */
        .toast-info .toast-icon-badge {
            background: #e5eefe;
        }

        .toast-info .toast-progress {
            background: linear-gradient(90deg, #3b82f6, #2563eb);
        }

        @media (max-width: 480px) {
            #toast-container {
                left: 12px !important;
                right: 12px !important;
                top: 12px !important;
            }

            .toast-item {
                max-width: 100% !important;
                min-width: 0 !important;
            }
        }
    </style>

    <div id="toast-container"
        style="position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;">

        @if (session('success'))
            <div class="toast-item toast-success">
                <div class="toast-icon-badge">
                    <svg fill="none" stroke="#16a34a" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="toast-body">
                    <p class="toast-title">Success</p>
                    <p class="toast-msg">{{ session('success') }}</p>
                </div>
                <button class="toast-close" onclick="closeToast(this)">&times;</button>
                <div class="toast-progress"></div>
            </div>
        @endif

        @if (session('error'))
            <div class="toast-item toast-error">
                <div class="toast-icon-badge">
                    <svg fill="none" stroke="#dc2626" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="toast-body">
                    <p class="toast-title">Error</p>
                    <p class="toast-msg">{{ session('error') }}</p>
                </div>
                <button class="toast-close" onclick="closeToast(this)">&times;</button>
                <div class="toast-progress"></div>
            </div>
        @endif

        @if (session('warning'))
            <div class="toast-item toast-warning">
                <div class="toast-icon-badge">
                    <svg fill="none" stroke="#d97706" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                </div>
                <div class="toast-body">
                    <p class="toast-title">Warning</p>
                    <p class="toast-msg">{{ session('warning') }}</p>
                </div>
                <button class="toast-close" onclick="closeToast(this)">&times;</button>
                <div class="toast-progress"></div>
            </div>
        @endif

        @if (session('info'))
            <div class="toast-item toast-info">
                <div class="toast-icon-badge">
                    <svg fill="none" stroke="#2563eb" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z" />
                    </svg>
                </div>
                <div class="toast-body">
                    <p class="toast-title">Info</p>
                    <p class="toast-msg">{{ session('info') }}</p>
                </div>
                <button class="toast-close" onclick="closeToast(this)">&times;</button>
                <div class="toast-progress"></div>
            </div>
        @endif

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="toast-item toast-error">
                <div class="toast-icon-badge">
                    <svg fill="none" stroke="#dc2626" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="toast-body">
                    <p class="toast-title">Validation Error</p>
                    @foreach ($errors->all() as $error)
                        <p class="toast-msg">{{ $error }}</p>
                    @endforeach
                </div>
                <button class="toast-close" onclick="closeToast(this)">&times;</button>
                <div class="toast-progress"></div>
            </div>
        @endif

    </div>

    <script>
        function closeToast(btn) {
            const toast = btn.closest('.toast-item');
            toast.classList.add('hide');
            setTimeout(() => toast.remove(), 350);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast-item');
            toasts.forEach(function(toast) {
                // Slide in
                setTimeout(() => toast.classList.add('show'), 50);

                // Auto dismiss after 4s
                setTimeout(function() {
                    toast.classList.add('hide');
                    setTimeout(() => toast.remove(), 350);
                }, 4000);
            });
        });
    </script>

@endif
