const segmentSelector=document.getElementById('segment');
const segmentDescSelector=document.getElementById('segmentDesc');
segmentSelector.addEventListener('change',(e)=>{
    e.preventDefault();

    const form = document.getElementById('segmentForm');
    const prePayload = new FormData(form);
    const payload = new URLSearchParams(prePayload);

    fetch('/get/segments',{
        method: 'post',
        body: payload,
    })  .then((resp)=> resp.json())
        .then((segments)=> {
            segmentDescSelector.innerHTML='';
            segments.forEach(showSegmentDesc)
        })

})

function showSegmentDesc(segmentDesc){
    let option = document.createElement('option')
    option.value=segmentDesc;
    option.innerText=segmentDesc;
    segmentDescSelector.appendChild(option);
}
