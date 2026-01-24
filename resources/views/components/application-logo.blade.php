<svg xmlns="http://www.w3.org/2000/svg" viewBox="147 0 206 151" {{ $attributes }}>
     <defs>
          <filter id="editing-hole" width="300%" height="300%" x="-100%" y="-100%">
               <feFlood flood-color="#000" result="black" />
               <feMorphology in="SourceGraphic" operator="dilate" radius="2" result="erode" />
               <feGaussianBlur in="erode" result="blur" stdDeviation="4" />
               <feOffset dx="2" dy="2" in="blur" result="offset" />
               <feComposite in="offset" in2="black" operator="atop" result="merge" />
               <feComposite in="merge" in2="SourceGraphic" operator="in" result="inner-shadow" />
          </filter>
     </defs>
     <img src="{{ asset('images/favicon.ico') }}" 
     alt="Logo"
     {{ $attributes->merge(['class' => 'h-32 w-auto object-contain']) }}>
</svg>