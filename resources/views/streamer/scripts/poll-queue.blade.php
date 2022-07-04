<script>
$(document).ready(() => {
    
    refreshPollQueue()

    function refreshPollQueue() {
        console.log('refreshing poll queue')
    }

    function updatePollQueue(pollQueue) {
        console.log(pollQueue, 'updated poll queue')
    }

    Alpine.effect(() => {
        const pollQueue = Alpine.store('poll_queue').queue
        updatePollQueue(pollQueue)
    })
})

</script>