<div class="js-cookie-consent cookie-consent">
<script>
    $(document).ready(function() {
        const Swal = window.Swal
        Swal.fire({
                title: 'Do you agree to accept our cookies?',
                text: "Our website uses cookies to keep you logged in, and helps us understand how the site is being used",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes, I agree'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.laravelCookieConsent.consentWithCookies()
                } else {
                    history.back()
                }
        })
    })
</script>
</div>
