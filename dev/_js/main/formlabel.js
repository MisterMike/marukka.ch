/*!
 * classie - class helper functions
 * from bonzo https://github.com/ded/bonzo
 * 
 * classie.has( elem, 'my-class' ) -> true/false
 * classie.add( elem, 'my-new-class' )
 * classie.remove( elem, 'my-unwanted-class' )
 * classie.toggle( elem, 'my-class' )
 */

/*jshint browser: true, strict: true, undef: true */
/*global define: false */
$(document).ready(() => {

    (window => {
        // class helper functions from bonzo https://github.com/ded/bonzo

        function classReg( className ) {
            return new RegExp(`(^|\\s+)${className}(\\s+|$)`);
        }

        // classList support for class management
        // altho to be fair, the api sucks because it won't accept multiple classes at once
        let hasClass;

        let addClass;
        let removeClass;

        if ( 'classList' in document.documentElement ) {
            hasClass = ({classList}, c) => classList.contains( c );
            addClass = ({classList}, c) => {
                classList.add( c );
            };
            removeClass = ({classList}, c) => {
                classList.remove( c );
            };
        }
        else {
            hasClass = ({className}, c) => classReg( c ).test( className );
            addClass = (elem, c) => {
                if ( !hasClass( elem, c ) ) {
                    elem.className = `${elem.className} ${c}`;
                }
            };
            removeClass = (elem, c) => {
                elem.className = elem.className.replace( classReg( c ), ' ' );
            };
        }

        function toggleClass( elem, c ) {
            const fn = hasClass( elem, c ) ? removeClass : addClass;
            fn( elem, c );
        }

        const classie = {
            // full names
            hasClass,
            addClass,
            removeClass,
            toggleClass,
            // short names
            has: hasClass,
            add: addClass,
            remove: removeClass,
            toggle: toggleClass
        };

        // transport
        if ( typeof define === 'function' && define.amd ) {
            // AMD
            define( classie );
        } else {
            // browser global
            window.classie = classie;
        }
    })(window);

    function inputFilled(){

        $('.input__field').on( 'focus', function() {

            $(this).children('.input__field--wrapper').addClass('input--filled');

        });

        $('.input__field').on( 'blur', function() {

            if ($(this).val() == '') {
                $(this).parent('.input__field--wrapper').removeClass('input--filled');
            }
        });

    }

    (() => {


        // trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
        if (!String.prototype.trim) {
            (() => {
                // Make sure we trim BOM and NBSP
                const rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
                String.prototype.trim = function() {
                    return this.replace(rtrim, '');
                };
            })();
        }

        [].slice.call( document.querySelectorAll( '.input__field' ) ).forEach( inputEl => {
            // in case the input is already filled..
            if( inputEl.value.trim() !== '' ) {
                classie.add( inputEl.parentNode, 'input--filled' );
            }

            // events:
            inputEl.addEventListener( 'focus', onInputFocus );
            inputEl.addEventListener( 'blur', onInputBlur );
        } );

        function onInputFocus({target}) {
            classie.add( target.parentNode, 'input--filled' );
        }

        function onInputBlur({target}) {
            if( target.value.trim() === '' ) {
                classie.remove( target.parentNode, 'input--filled' );
            }
        }
    })();

});