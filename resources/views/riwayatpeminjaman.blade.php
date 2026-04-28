@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Loan History') }}</h1>
    <p class="text-slate-600 dark:text-slate-400">{{ __('Track your active loans and return deadlines') }}</p>
</div>

<!-- Main Table -->
<div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="js-smart-table w-full text-left border-collapse" id="riwayatTable" data-filter-fields="status">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                <tr>
                    <th class="p-4 font-medium">{{ __('No') }}</th>
                    <th class="p-4 font-medium">{{ __('Book Title') }}</th>
                    <th class="p-4 font-medium">{{ __('Loan Date') }}</th>
                    <th class="p-4 font-medium">{{ __('Status') }}</th>
                    <th class="p-4 font-medium">{{ __('Remaining Time') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700" id="tableRiwayatPeminjaman">
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-500">
                        <svg class="animate-spin h-8 w-8 text-indigo-500 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('Loading data...') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Translations passed from Blade to JS
    const translations = {
        "Active": "{{ __('Active') }}",
        "Returned": "{{ __('Returned') }}",
        "Pending Approval": "{{ __('Pending Approval') }}",
        "Return Requested": "{{ __('Request Return') }}",
        "Waiting Action": "{{ __('Waiting Action') }}",
        "Completed": "{{ __('Completed') }}",
        "Calculating...": "{{ __('Calculating...') }}",
        "Overdue!": "{{ __('Overdue!') }}",
        "Unknown Title": "{{ __('Unknown Title') }}",
        "You haven't borrowed any books yet.": "{{ __('No active loans found.') }}"
    };

    // Global variable to store intervals so we can clear them on refresh
    var countdownIntervals = {};

    function loadDataRiwayat() {
        $.ajax({
            url: "{{ url('/riwayat/json') }}",
            type: "GET",
            dataType: "json",
            success: function(response) {
                var data = response.data; // Extract the actual array from response object
                var rows = "";
                
                // Clear existing intervals
                for (var key in countdownIntervals) {
                    clearInterval(countdownIntervals[key]);
                }
                countdownIntervals = {};

                if (!data || data.length === 0) {
                    rows = `<tr><td colspan="5" class="p-8 text-center text-slate-500 dark:text-slate-400">${translations["You haven't borrowed any books yet."]}</td></tr>`;
                } else {
                    $.each(data, function(index, item) {
                        var statusBadge = '';
                        var statusRaw = item.status.toLowerCase(); // Ensure consistent comparison

                        if(statusRaw === 'dipinjam') {
                            statusBadge = `<span class="bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 rounded-full px-3 py-1 text-xs font-medium">${translations['Active']}</span>`;
                        } else if(statusRaw === 'dikembalikan') {
                            statusBadge = `<span class="bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 rounded-full px-3 py-1 text-xs font-medium">${translations['Returned']}</span>`;
                        } else if(statusRaw === 'pending_pinjam') {
                            statusBadge = `<span class="bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 rounded-full px-3 py-1 text-xs font-medium">${translations['Pending Approval']}</span>`;
                        } else if(statusRaw === 'pending_kembali') {
                            statusBadge = `<span class="bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20 rounded-full px-3 py-1 text-xs font-medium">${translations['Return Requested']}</span>`;
                        } else {
                            statusBadge = `<span class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-full px-3 py-1 text-xs font-medium">${item.status}</span>`;
                        }

                        // Determine deadline text/timer
                        var deadlineHtml = '-';
                        if (statusRaw === 'dipinjam' && item.tanggal_pinjam) {
                            var uniqueId = 'timer_' + item.id;
                            deadlineHtml = `<span id="${uniqueId}" class="font-mono text-indigo-600 dark:text-indigo-300">${translations['Calculating...']}</span>`;
                            
                            // Start countdown for this item
                            startCountdown(item.tanggal_pinjam, uniqueId);
                        } else if (statusRaw === 'dikembalikan') {
                            deadlineHtml = `<span class="text-slate-500 dark:text-slate-400">${translations['Completed']}</span>`;
                        } else if (statusRaw.includes('pending')) {
                            deadlineHtml = `<span class="text-slate-500 dark:text-slate-400 text-xs italic">${translations['Waiting Action']}</span>`;
                        }

                        // Correct property name for book title is 'judul' not 'judul_buku' based on DataController query
                        var bookTitle = item.judul || item.judul_buku || translations['Unknown Title'];

                        rows += `
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors" data-status="${statusRaw}">
                                <td class="p-4 text-slate-500 dark:text-slate-400">${index + 1}</td>
                                <td class="p-4 font-medium text-slate-900 dark:text-white">${bookTitle}</td>
                                <td class="p-4 text-slate-600 dark:text-slate-300">${item.tanggal_pinjam}</td>
                                <td class="p-4">${statusBadge}</td>
                                <td class="p-4">${deadlineHtml}</td>
                            </tr>
                        `;
                    });
                }
                $('#tableRiwayatPeminjaman').html(rows);
            },
            error: function(xhr, status, error) {
                console.error("Error loading history:", error);
                $('#tableRiwayatPeminjaman').html('<tr><td colspan="5" class="p-8 text-center text-red-500">{{ __('Failed to load data.') }}</td></tr>');
            }
        });
    }

    function startCountdown(tanggalPinjam, elementId) {
        // Assume 5 days loan period as per original logic if not specified
        // Format: YYYY-MM-DD HH:MM:SS
        var borrowDate = new Date(tanggalPinjam.replace(/-/g, "/")); // Replace - with / for better cross-browser support
        var deadline = new Date(borrowDate);
        deadline.setDate(borrowDate.getDate() + 5); // Add 5 days

        var interval = setInterval(function() {
            var now = new Date().getTime();
            var distance = deadline.getTime() - now;

            var element = document.getElementById(elementId);
            if (!element) {
                clearInterval(interval);
                return;
            }

            if (distance < 0) {
                clearInterval(interval);
                element.innerHTML = "<span class='text-red-400 font-bold'>Overdue!</span>";
                return;
            }

            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            element.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
        }, 1000);

        countdownIntervals[elementId] = interval;
    }

    $(document).ready(function() {
        loadDataRiwayat();
        // Optional: Auto-refresh every 60 seconds
        // setInterval(loadDataRiwayat, 60000);
    });
</script>
@endsection
