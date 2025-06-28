jQuery(document).ready(function($) {
    $('#bdubk-bulk-delete-form').on('submit', function(e) {
        e.preventDefault();
        
        if (!confirm('Are you sure you want to delete these users? This action cannot be undone.')) {
            return;
        }

        var form = $(this);
        var progressContainer = $('#bdubk-progress-container');
        var progressBar = $('#bdubk-progress-bar-fill');
        var progressText = $('#bdubk-progress-text');
        var processedCount = $('#bdubk-processed-count span');
        var deletedCount = $('#bdubk-deleted-count span');
        
        // Show progress container
        progressContainer.css({
            'display': 'block',
            'opacity': 1,
            'height': 'auto'
        });
        progressText.text(bdubk_ajax.processing_text);
        
        // Disable submit button
        $('#submit').prop('disabled', true).addClass('processing-glow');
        
        // Get form data properly
        var formData = new FormData();
        formData.append('action', 'bdubk_get_user_count');
        formData.append('nonce', bdubk_ajax.nonce);
        formData.append('keyword', $('input[name="bdubk_keyword"]').val());
        
        // Add checked search fields
        $('input[name="bdubk_search_fields[]"]:checked').each(function() {
            formData.append('search_fields[]', $(this).val());
        });

        // First AJAX call to get total count
        $.ajax({
            url: bdubk_ajax.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var totalUsers = response.data.total_users;
                    var batchSize = parseInt($('input[name="bdubk_batch_size"]').val()) || 100;
                    
                    if (totalUsers === 0) {
                        progressText.text('No users found matching your criteria.');
                        $('#submit').prop('disabled', false).removeClass('processing-glow');
                        return;
                    }
                    
                    // Start processing batches
                    processBatch(0, totalUsers, batchSize);
                } else {
                    progressText.text('Error: ' + response.data);
                    $('#submit').prop('disabled', false).removeClass('processing-glow');
                }
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.responseJSON && xhr.responseJSON.data ? xhr.responseJSON.data : error;
                progressText.text('Error: ' + errorMessage);
                $('#submit').prop('disabled', false).removeClass('processing-glow');
                console.error('AJAX Error:', xhr.responseText);
            }
        });
        
        function processBatch(offset, totalUsers, batchSize) {
            var batchFormData = new FormData();
            batchFormData.append('action', 'bdubk_process_batch');
            batchFormData.append('nonce', bdubk_ajax.nonce);
            batchFormData.append('keyword', $('input[name="bdubk_keyword"]').val());
            batchFormData.append('offset', offset);
            batchFormData.append('batch_size', batchSize);
            
            $('input[name="bdubk_search_fields[]"]:checked').each(function() {
                batchFormData.append('search_fields[]', $(this).val());
            });

            $.ajax({
                url: bdubk_ajax.ajax_url,
                type: 'POST',
                data: batchFormData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var processed = response.data.processed;
                        var deleted = response.data.deleted;
                        
                        // Update counters
                        var newProcessed = parseInt(processedCount.text()) + processed;
                        var newDeleted = parseInt(deletedCount.text()) + deleted;
                        
                        processedCount.text(newProcessed);
                        deletedCount.text(newDeleted);
                        
                        // Update progress
                        var percentComplete = Math.min(Math.round((offset + batchSize) / totalUsers * 100), 100);
                        progressBar.css('width', percentComplete + '%');
                        progressText.text('Processing... (' + percentComplete + '%)');
                        
                        // Process next batch if needed
                        if (offset + batchSize < totalUsers) {
                            processBatch(offset + batchSize, totalUsers, batchSize);
                        } else {
                            // Complete
                            progressText.text(bdubk_ajax.complete_text + ' ' + newDeleted + ' users deleted.');
                            $('#submit').prop('disabled', false).removeClass('processing-glow');
                        }
                    } else {
                        progressText.text('Error: ' + response.data);
                        $('#submit').prop('disabled', false).removeClass('processing-glow');
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.data ? xhr.responseJSON.data : error;
                    progressText.text('Error: ' + errorMessage);
                    $('#submit').prop('disabled', false).removeClass('processing-glow');
                    console.error('AJAX Error:', xhr.responseText);
                }
            });
        }
    });
});