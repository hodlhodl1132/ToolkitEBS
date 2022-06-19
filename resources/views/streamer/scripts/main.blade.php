<script>
    $(document).ready(function () {
        const queryString = window.location.search
        const urlParams = new URLSearchParams(queryString)
        const tab = urlParams.get('tab')
        if (tab !== null)
        {
            $('.menu .item').tab('change tab', tab)
        } else {
            $('.menu > .item:first-of-type').addClass('active')
            $('div.tabs > div:nth-child(2)').addClass('active')
            $('.menu .item').tab()
            
        }
    })
</script>