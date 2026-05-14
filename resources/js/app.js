import './bootstrap';

window.Echo.channel('submission-channel')
.listen('.submission.updated', (e) => {

    console.log('Submission Updated');

    console.log(e.submission);

});
