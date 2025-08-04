function runToast(message, status, duration) {
  
  const CSS = `
  .eztoast {
    padding: 10px;
    min-width:100px;
    width: fit-content;
    height: auto;
    position: fixed;
    /*bottom:calc((var(--index,1) * 50px) + 10px);*/
    right:50px;
    top: 150px;
    border-radius: 5px;
    opacity: 0;
    background: color-mix(in srgb,var(--status, #444) 50%, white);
    pointer-events:none;
    z-index:10000;
    @media(width < 992px) {
      right: 50%;
      top: 80%;
      transform: translate(50%);
    }
    &:after{
      content:'';
      position:absolute;
      top:0;
      left:-5px;
      border-radius:5px 0px 0px 5px;
      height:100%;
      width:10px;
      background:color-mix(in srgb, var(--status,#444) 80%,black);
    } 
    & p {
      text-shadow: 0px 0px 0px #000;
      color:#000;
      margin: 0;
    }
  }

  .toast-show {
    animation: delete var(--duration, 3s) ease forwards;
    @media (width < 992px){
    animation: delete-mob var(--duration, 3s) ease forwards;
    }
  }

  @keyframes delete {
    0% {
      translate:0 -100px;
      display:block;
      opacity:1;
    }
    10% {
      translate:0 0px;
      display:block;
      opacity:1;
    }
    90% {
      translate:0 0px;
      display:block;
      opacity:1;
    }
    100% {
      translate:0 100px;
      opacity:0;
      display:none;
    }
  }
  @keyframes delete-mob {
    0% {
      translate:0 100px;
      display:block;
      opacity:1;
    }
    10% {
      translate:0 0px;
      display:block;
      opacity:1;
    }
    90% {
      translate:0 0px;
      display:block;
      opacity:1;
    }
    100% {
      translate:0 -100px;
      opacity:0;
      display:none;
    }
  }
  `;
  if (!document.getElementById('eztoast-styles')) {
    let styleTag = document.createElement('style');
    styleTag.id = 'eztoast-styles';
    styleTag.innerHTML = CSS;
    document.head.appendChild(styleTag);
  }
  
  let toast = document.createElement('div');
  toast.classList.add('eztoast');
  toast.setAttribute('role', 'status');
  toast.setAttribute('aria-live', 'polite');

    // checking any toast is on screen already
  // if (localStorage.getItem('toast-count') == null) {
  //   localStorage.setItem('toast-count', '0');
  // }

  document.body.appendChild(styleTag);
  document.body.appendChild(toast);

     // Increamenting based on previous toast
  //  let count = parseInt(localStorage.getItem('toast-count'));
  //  toast.dataset.count = count++;
  //  localStorage.setItem('toast-count', parseInt(count));

  let statusObject = {
    danger: '#dc3545',
      warning: '#ffc107',
      success: '#198754',
      info: '#0d6efd',
  }
  if (status) {
    toast.style.setProperty('--status', statusObject[status.toLowerCase()])
  }
  if (duration) {
    const finalDuration = typeof duration === 'number' ? `${duration}ms` : duration;
    toast.style.setProperty('--duration', finalDuration);
  }
  // toast.style.setProperty('--index', toast.dataset.count)
  toast.innerHTML = `<p>${message}</p>`;
  toast.classList.add('toast-show');
  toast.addEventListener('animationend', () => {
    document.body.removeChild(toast);
    document.body.removeChild(styleTag);
    // localStorage.removeItem('toast-count');
  })
}