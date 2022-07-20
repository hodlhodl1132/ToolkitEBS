<script>
$(document).ready(() => {
    
    refreshPollQueue()

    function refreshPollQueue() {
        $.ajax({
            url: "{{ route('queued-polls.index', $user->provider_id) }}",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                if (response.data !== undefined && response.data.length > 0) {
                    Alpine.store('poll_queue').queue = response.data
                }
            }
        })
    }

    function updatePollQueue(pollQueue) {
        $('#poll-queue-container tbody').html('')

        if (pollQueue.length === 0) {
            $('#poll-queue-container').css('display', 'none')
            return
        }

        $('#poll-queue-container').css('display', 'block')
        
        pollQueue.forEach(element => {
            let pollOptions = element.options.map(option => {
                return `<div class="ui label">${option.label}</div>`
            }).join('')
            let timestamp = new Date(element.created_at).getTimezoneOffset() * 60000
            let date = new Date(element.created_at) - timestamp
            let dateString = new Intl.DateTimeFormat('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric',
                hour12: false
            }).format(date)
            
            $('#poll-queue-container tbody').append(`
                    <tr>
                        <td>${element.title}</td>
                        <td>${element.length} minute(s)</td>
                        <td>${pollOptions}</td>
                        <td>${dateString}</td>
                        <td>
                            <div class="ui buttons">
                                <button onclick="removePollFromQueue(${element.id})" class="ui icon basic button red" data-tooltip="{{ __('Delete Poll') }}">
                                    <i class="trash icon"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
            `)
        });
    }

    Alpine.effect(() => {
        const pollQueue = Alpine.store('poll_queue').queue
        updatePollQueue(pollQueue)
    })
})

function removePollFromQueue(pollId) {
    $.ajax({
        url: "{{ route('queued-polls.delete', ':id') }}".replace(':id', pollId),
        type: "DELETE",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: (response) => {
            if (response.success !== undefined) {
                Alpine.store('poll_queue').queue = Alpine.store('poll_queue').queue.filter(element => element.id !== pollId)
                window.InfoToast('Poll removed from queue')
            } else {
                window.ErrorToast(response.message)
            }
        }
    })
}
</script>