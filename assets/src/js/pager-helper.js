function navigateForm(button, direction) {
    // Get the current page from the parent div
    const currentPageDiv = button.closest('.gform_page');
	const currentPageId = currentPageDiv.id;
  
  const currentFormDiv = button.closest( 'form' );
  const currentFormId = currentFormDiv.getAttribute('data-formid');
  

// Use the replace function to remove the known parts
const pageNumber = currentPageId.replace(`gform_page_${currentFormId}_`, '');
  
    // Parse the current page number from the ID (assuming format like gform_page_4_1)
    const currentPageNumber = parseInt(currentPageId.split('_').pop());
  console.log( currentPageNumber );

    // Determine the new page number based on direction (1 for next, -1 for previous)
    const newPageNumber = currentPageNumber + direction;

  	//const currentPageString = 'gform_page_' + currentFormId + '_' + currentPageNumber;
    const newPageId = 'gform_page_' + currentFormId + '_' + newPageNumber;
  
    // Construct the new page's ID
    //const newPageId = currentPageId.replace('gform_page_' + currentFormId + '_' + currentPageNumber, newPageNumber);

    // Hide the current page and show the new page
    document.getElementById(currentPageId).style.display = 'none';
    const newPage = document.getElementById(newPageId);
    if (newPage) {
        newPage.style.display = 'block';
    }
}


jQuery(document).on('gform_post_render_d', function( event, form_id, current_page ) {
  
    console.log( 'gform_post_render fired!', form_id, current_page );

    // Attach the function to the "next" and "previous" buttons
    document.querySelectorAll('.gform_next_button').forEach(button => {
        console.log( 'next button' , this );
        button.addEventListener('click', function(e) {
            console.log(e, this );
            e.preventDefault;
            e.stopPropagation();
            navigateForm(this, 1); // Move to the next page
        });
    });

    document.querySelectorAll('.gform_previous_button').forEach(button => {
        button.addEventListener('click', function(e) {
            console.log(e, this );
            e.preventDefault();
            e.stopPropagation();
            navigateForm(this, -1); // Move to the previous page
        });
    });

});