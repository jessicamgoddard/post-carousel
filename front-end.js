const outer = document.querySelector( '.outer-container' );
const inner = document.querySelector( '.inner-container' );
const horiz = document.querySelector( '.horizontal-container' );

const calcDynamicHeight = ( object ) => {

  const objectWidth = object.scrollWidth;
  const vw = window.innerWidth;
  const vh = window.innerHeight;
  const dynamicHeight = objectWidth - vw + vh + 150;

  return dynamicHeight;

}

// Set dynamic height
outer.style.height = calcDynamicHeight( horiz ) + 'px';

// Set dynamic transform as user scrolls
window.addEventListener( 'scroll', () => {
  horiz.style.transform = 'translateX( ' + -inner.offsetTop + 'px )';
})
