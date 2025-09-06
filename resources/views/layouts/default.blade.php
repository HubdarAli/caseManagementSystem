<!doctype html>
<html lang="en">

<head>
    @include('includes.head')
    {{-- {{ added irfan }} --}}
    @stack('style')
    {{-- {{ added irfan }} --}}

</head>

<body>

    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow @if (!Str::contains(request()->url(), 'dashboard')) sidebar-mini @endif">

        @yield('loader')


        <!-- Sidebar -->
        {{-- @if (!request()->has('no_sidebar'))
            <nav id="sidebar" aria-label="Main Navigation">
                @include('includes.sidebar')
            </nav>
        @endif --}}
        <!-- END Sidebar -->

        <!-- Header -->
        <header id="page-header">
            @include('includes.header')
            @include('includes.navbar')
        </header>
        <!-- END Header -->
        <!-- Main Container -->
        <main id="main-container">
            @yield('content')
        </main>
        <!-- END Main Container -->

        <!-- Footer -->
        <footer id="page-footer" class="bg-body-light">
            @include('includes.footer')
        </footer>
        <!-- END Footer -->
    </div>
    <!-- END Page Container -->

    @include('includes.foot')
    {{-- {{ added irfan }} --}}
    @stack('script')
    {{-- {{ added irfan }} --}}

    <script>
        var notificationListing;
        $(document).on('click', '.notificationUrl', function(event) {
            event.preventDefault();
            var notificationId = $(this).data('id');
            $.ajax({
                url: '/notifications/redirect/' + notificationId,
                method: 'GET',

                success: function(response) {
                    var notificationUrl = response.notificationUrl;
                    var notifications = response.notificationsLatest;

                    if (notificationUrl != "") {
                        location.href = notificationUrl;
                    }
                    var notificationList = document.querySelector('.nav-items');

                    notificationList.innerHTML = '';

                    if (notifications.length > 0) {
                        notifications.forEach(function(notification) {
                            var li = document.createElement('li');

                            var a = document.createElement('a');
                            a.classList.add('text-dark', 'd-flex', 'py-2', 'notificationUrl');
                            a.setAttribute('data-id', notification.id);
                            a.setAttribute('data-url', notification.redirect_url);

                            var divIcon = document.createElement('div');
                            divIcon.classList.add('flex-shrink-0', 'me-2', 'ms-3');

                            var icon = document.createElement('i');
                            icon.classList.add('fa', 'fa-fw', 'text-success');
                            divIcon.appendChild(icon);

                            var divContent = document.createElement('div');
                            divContent.classList.add('flex-grow-1', 'pe-2');

                            var divMessage = document.createElement('div');
                            divMessage.classList.add('fw-semibold');
                            divMessage.textContent = notification.message;

                            var spanTime = document.createElement('span');
                            spanTime.classList.add('fw-medium', 'text-muted');
                            spanTime.textContent = notification.created_at_human;

                            divContent.appendChild(divMessage);
                            divContent.appendChild(spanTime);

                            a.appendChild(divIcon);
                            a.appendChild(divContent);

                            li.appendChild(a);
                            notificationList.appendChild(li);
                        });
                    } else {
                        // If no notifications, show a message
                        var li = document.createElement('li');
                        var div = document.createElement('div');
                        div.classList.add('text-center', 'py-2');
                        div.textContent = 'No notifications';
                        li.appendChild(div);
                        notificationList.appendChild(li);
                    }
                    notificationListing.ajax.reload();


                }

            });
        });

        // Start polling every 5 seconds
        let importKey = "{{ session('cacheKey') }}"; // Replace with the key you received
        const polling = setInterval(() => fetchImportStatus(importKey), 3000);
        let hitsCount = 0;

        function fetchImportStatus(importKey) {

            if (hitsCount >= 5) {
                clearInterval(polling); // Stop polling
                $('.progress-container-job').hide();
                importKey = null;
            }

            if (importKey != "" && importKey != null) {


                fetch(`/import-status/${importKey}`)
                    .then(response => response.json())
                    .then(data => {
                        // console.log(data);
                        if (data.status === 'system-error') {
                            clearInterval(polling); // Stop polling
                            $('.progress-container-job').hide();
                            importKey = null;
                            $('#alertModal').modal('show').find('.modal-body').text(data.message);
                            return false;
                        }

                        if (data.status === 'error') {
                            // clearInterval(polling); // Stop polling
                            $('.progress-container-job').hide();
                            // importKey = null;
                            hitsCount++;
                            return false;
                        }

                        if (data.status === 'completed') {
                            clearInterval(polling); // Stop polling
                            $('.progress-container-job').hide();


                            if (data.modal_data != null) {

                                $('#gt-modal').find('.modal-body').empty();
                                $.each(data.modal_data, function(index, item) {
                                    if (item !== '') {

                                        $('#gt-modal').find('.modal-body').append(`
                                        <div class="row mb-0">
                                            <strong class="col-6 pl-1"> ${index.replace(/_/g, ' ').replace(/^\w/, c => c.toUpperCase())}
                                                </strong>
                                                <span class="col-6 pl-1">${item}</span>
                                                </div>`);
                                    }
                                });

                                $('#gt-modal').modal('show');
                            }

                            importKey = null;

                            return false;
                        }
                        // Update the UI with the data
                        // document.getElementById('status').innerText = data.status;
                        let progress = (data.processed_records / data.total_records) * 100;

                        // document.getElementById('progress').innerText =
                        //     `${data.processed_records} / ${data.total_records}`;

                        $('.progress-container-job').show();
                        $('#job-progress').find('div').css('width', progress.toFixed() + '%');
                        $('#job-progress').attr('aria-valuenow', progress.toFixed());
                        $('#job-progress').find('div span').text(progress.toFixed() + '%');


                    })
                    .catch(error => console.error('Error:', error));
            }
        }
    </script>

</body>

</html>
