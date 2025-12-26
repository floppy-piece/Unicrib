<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>UniCrib</title>
        <x-icon/>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        
        <!-- Styles -->
        <style>
        * {
            font-family: 'Inter', sans-serif;
            overflow-right:hidden;
        
        }
        body{
            width:auto;
        }
        /* General styling for the items */
        .item {
            flex: 33.3%; 
            padding: 20px;
            box-sizing: border-box; /* Ensures padding/border are included in the width */
            /* Use padding to simulate an aspect ratio or ensure height responsiveness */
            padding-bottom: 5%; /* Example: maintains a certain height/width ratio */
            height: fit-content; /* Important for the padding trick to work */
            border-radius:10px;
            justify-content:space-around;
        }

        /* Default styles for large screens (row layout) */
        .containery{
            display: flex;
            flex-wrap: wrap; /* Allows items to wrap to the next line on smaller screens */
            height: fit-content; /* Height adjusts to content */
            width: 100%;
            justify-content:space-around;
        }

        

        /* Media query for small screens (e.g., screen width less than 600px) */
        @media screen and (max-width: 600px) {
        .containery {
            /* Changes the layout direction to a column for small screens */
            flex-direction: column; 
        }
        .item {
            flex: 100%; /* Items take full width */
            padding-bottom: 100px; /* Adjust height for single column */
            height: auto; 
            margin-bottom:20px;
        }
        }

        
        .font-serif {
            font-family: 'Source Serif Pro', serif;
        }
        
        .gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .gradient-accent {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .text-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .text-gradient-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .card-gradient {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        }
        
        .dark .card-gradient {
            background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .hover-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 35px 60px -12px rgba(102, 126, 234, 0.2);
        }
        
        /*.section-padding {
            padding-top: 7rem;
            padding-bottom: 7rem;
        }*/
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-25px); }
            100% { transform: translateY(0px); }
        }
        
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .border-gradient {
            border-width: 2px;
            border-style: solid;
            border-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%) 1;
        }
        
        .badge-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stat-number {
            font-size: 4rem;
            line-height: 1;
            font-weight: 800;
            background: linear-gradient(135deg, #666eea 0%, #769ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align:center;
        }
        
        .dark .stat-number {
            background: linear-gradient(135deg, #a78bfa 0%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        a{
            all: unset;
  cursor: pointer;
        }
    </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body style="background:linear-gradient(rgba(35,0,70,0.8),rgba(3,56,89,0.8))">            
        <div>  
            
                
            <header style="background-color:#111827;color:#9ca3af;box-shadow: inset 0 0px 0 0 #FFFFFF;width:100%;display:sticky;padding-bottom:30px;overflow:hidden;" >                
                
                    <div style="display:flex;justify-content:center;text-align:center;align-items:center;">
                        <x-application-logo style="border-radius:50px;height:50px;width:50px;object-fit:cover;"/>                            
                        <h1 style="font-size:50px;font-weight:bold;">UniCrib</h1>
                    </div>
                    <x-non-nav/>
                    <div style="align-items:center;justify-content:center;text-align:right;display:block;margin-right:30px;">
                        @if (Route::has('login'))
                            <livewire:welcome.navigation/>
                        @endif
                    </div>
                
            </header>
            
            <section class="pt-32 pb-24 overflow-hidden relative" style="border: top radius -60deg;">
               
                <div class="container"style="background-image:url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'),linear-gradient(rgba(180, 60, 250, 0.5), rgba(0, 0, 2, 0.5));background-repeat:no-repeat;background-size:cover;background-blend-mode: darken;position:relative;z-index:10;box-shadow: inset 0 0px 0 0 #FFFFFF;">
                    <div class="flex flex-col "style="align-items:center;justify-content:center;"style="padding:10px;display:flex;flex-direction:comlumn;">
                        <!-- Hero Content -->
                        <div class="lg:w-1/2 mb-16 lg:mb-0 reveal"style="text-align:center;justify-content:center;"style="padding:10px;">
                            <div class="inline-flex items-center space-x-3 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-full px-4 py-2 mb-8"style="text-align:center;justify-content:center;">
                                <span class="text-purple-700 dark:text-purple-500 font-medium text-sm">ELITE ACCOMMODATIONS</span>
                                <span class="text-purple-500">✦</span>
                            </div>
                            
                            <h1 class="text-5xl lg:text-6xl font-serif font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                                Find Your <span class="text-gradient-secondary">Perfect</span> Student Home
                            </h1>
                            
                            <p class="text-xl text-gray-600 dark:text-gray-300 mb-10 max-w-2xl leading-relaxed" style="color:rgb(255,255,255);">
                                Discover handpicked, premium student accommodations near your university. No more endless searching — just exceptional living spaces tailored to your needs.
                            </p>
                            <br>
                            <div style="display:flex;flex-direction:column;heght:200px;width:100%;border-radius:10px;">
                                <a href="#" style="border-radius:30px;font-size:25px;display:block;" >
                                    <x-primary-button>
                                        {{__('Explore Properties')}} 
                                    </x-primary-button>
                                </a>                                    
                            <br>
                                <a href="#" style="border-radius:30px;font-size:25px;text-decoration:underline;color:white;">
                                        Watch Tour
                                </a>                                        
                            </div>
                            <br>
                            <!-- Trust Indicators -->
                            <div style="align-text:center; justify-content:space-between;display:flex;padding:5px;">
                                <div class="text-center">
                                    <div style="color:white;">+4.9</div>
                                    <div style="color:rgb(228,128,228);">Student Rating</div>
                                </div>
                                
                                <div class="text-center"style="display:flex;flex-direction:column;text-align:center;justify-content:center;">
                                    <div style="color:white;">2,500+</div>
                                    <div style="color:rgb(228,128,228);">Verified Properties</div>
                                </div>
                                
                                <div class="text-center"style="display:flex;flex-direction:column;text-align:center;justify-content:center;">
                                    <div style="color:white;">24/7</div>
                                    <div style="color:rgb(228,128,228);">Support</div>
                                </div>
                            </div>
                        </div>
                        <br><br>
                        <!-- Hero Image -->
                        <div style="overflow:hidden;padding:5px;width:100%;">
                            
                            
                            <!-- Floating Card 1 -->
                            <div class="card-gradient hover-card" style="justify-content:center; background-color:none;border-radius:10px;width:100%;" >

                                    <div style="justify-content:center;padding:5px;">
                                        <div class="gradient-primary hover-card" style="font-size:36px;border-radius:30px;text-align:center;" >Campus Proximity</div>
                                        <div >Walking Distance</div>
                                    </div>
                                
                                <p class="text-sm text-gray-600 dark:text-gray-300" style="color:rgb(100,123,20);">Properties within 15-minute walk to campus gates.</p>
                            </div>
                            <br>
                            <!-- Floating Card 2 -->
                            <div class="card-gradient hover-card"style="justify-content:center; background-color:inherit;border-radius:10px;width:100%">
                                
                                
                                    <div style="justify-content:center;padding:5px;">
                                        <div class="gradient-secondary hover-card"style="font-size:36px;border-radius:30px;text-align:center;">Verified & Secure</div>
                                        <div>Trust & Safety</div>
                                    </div>
                                
                                <p class="text-sm text-gray-600 dark:text-gray-300"style="color:rgb(100,123,20);">All properties undergo thorough verification.</p>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="section-padding bg-white dark:bg-gray-800"style="width:100%;">
                <div class="container mx-auto px-6"style="width:100%;background: linear-gradient(rgba(0,0,0,0.9), rgba(68,0,100,0.5));">
                    <div class="text-center mb-16 reveal" style="padding-top:10px;">
                        <div style="text-align:center;">                           
                            <span class="text-gradient-secondary" style="font-size:20px;">WHY UniCrib</span>                            
                        </div>
                        <h2 style="color:white; font-weight:semi-bold;text-align:center;">
                            Redefining <span class="text-gradient-primary">Student Living</span>
                        </h2>
                        <p style="color:white;text-align:center;">
                            We've transformed the student housing experience from stressful to seamless, with premium accommodations and exceptional service.
                        </p>
                    </div>
                    
                    <div style="display:flex;justify-content:space-between;flex-direction:column;">
                        <!-- Feature 1 -->
                        <div class="card-gradient  hover-card reveal" style="border-radius:5px;margin-bottom:7px;padding:auto;text-align:center;height:140px;">                                    
                            <h3 class="gradient-primary"style="border-radius:70px;height:50px;">Curated Selection</h3>
                            <p class="text-gray-600 dark:text-gray-900">
                                Each property is personally inspected and selected for quality, location, and student needs.
                            </p>
                        </div>
                        
                        <!-- Feature 2 -->
                        <div class="card-gradient hover-card reveal" style="border-radius:5px;margin-bottom:7px;padding:auto;text-align:center;height:140px;">                            
                            <h3 class="gradient-secondary" style="border-radius:70px;height:50px;">Time-Saving</h3>
                            <p class="text-gray-600 dark:text-gray-900">
                                No more endless searching. Find your perfect home in minutes, not weeks.
                            </p>
                        </div>
                        
                        <!-- Feature 3 -->
                        <div class="card-gradient hover-card reveal" style="border-radius:5px;margin-bottom:7px;padding:auto;text-align:center;height:140px;">                                    
                            <h3 class="gradient-accent" style="border-radius:70px;height:50px;">Community</h3>
                            <p class="text-gray-600 dark:text-gray-900">
                                Connect with potential roommates and join student communities in your area.
                            </p>
                        </div>
                        
                        <!-- Feature 4 -->
                        <div class="card-gradient hover-card reveal" style="border-radius:5px;margin-bottom:7px;padding:auto;text-align:center;height:140px;">
                            <h3 class="gradient-primary"style="border-radius:70px;height:50px;">24/7 Support</h3>
                            <p class="text-gray-600 dark:text-gray-900"style="margin:auto">
                                Dedicated support team available anytime for questions, maintenance, or emergencies.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Property Showcase -->
            <section class="py-20 bg-gradient-to-br from-gray-50 to-purple-50 dark:from-gray-900 dark:to-purple-900/20">
                <div class="container mx-auto px-6">
                    <div class="flex flex-col lg:flex-row justify-between items-end mb-16 reveal">
                        <div >
                            <div style="text-align:center;">
                                
                                <span class="text-gradient-secondary"style="text-align:center;">FEATURED PROPERTIES</span>
                                
                            </div>
                            <h2 style="color:white; font-weight:semi-bold;text-align:center;">
                                Exceptional <span class="text-gradient-secondary">Accommodations</span>
                            </h2>
                            <p style="color:white;text-align:center;">
                                A selection of our premium student properties near top universities.
                            </p>
                        </div>
                        <a href="#" style="color:white;text-align:center;font-weight;bold;text-decoration:underline;">
                            View All <i class="fas fa-arrow-right ml-3 group-hover:translate-x-2 transition-transform duration-300"></i>
                        </a>
                    </div>
                    
                    <div class="containery">
                        <!-- Property 1 -->
                        <div class="group card-gradient item ">
                            <div style="text-align:center;">
                                <img src="{{asset('images/home.jpeg')}}" 
                                    alt="Modern apartment" 
                                    
                                    style="width:300px;object-fit:cover;border-radius:500px;height:450px;">
                                
                                
                            </div>
                            <div class=" badge-gradient "style="border-radius:20px;text-align:center;height:40px;align-items:center;justify-content:center;">
                                    $1,850/mo
                                </div>
                            <div class="p-8">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-900">The University Lofts</h3>
                                    
                                </div>
                                <div class="flex items-center text-gray-600 dark:text-gray-400 mb-6">
                                    <i class="fas fa-map-marker-alt mr-3 text-purple-500"></i>
                                    <span>0.4mi from Stanford University</span>
                                </div>
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-bed mr-2 text-purple-500"></i>
                                            <span class="font-medium">2 beds</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-bath mr-2 text-purple-500"></i>
                                            <span class="font-medium">2 baths</span>
                                        </div>
                                    </div>
                                    <span class="text-sm font-medium px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-full">Premium</span>
                                </div>
                                <a href="#">
                                    <x-primary-button>
                                    {{__('View Details')}}
                                    </x-primary-button>
                                </a>
                            </div>
                        </div>
                        <br>
                        <!-- Property 2 -->
                        <div class=" card-gradient item ">
                            <div style="text-align:center;align-items:center;justify-content:center;">
                                <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                    alt="Luxury studio"                                     
                                
                                    style="width:300px;object-fit:cover;border-radius:500px;height:450px;">
                                
                            </div>
                            <div class=" badge-gradient "style="border-radius:20px;text-align:center;height:40px;align-items:center;justify-content:center;">
                                    $2,100/mo
                                </div>
                            <div class="p-8">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-900">Campus View Apartments</h3>
                                    
                                </div>
                                <div class="flex items-center text-gray-600 dark:text-gray-400 mb-6">
                                    <i class="fas fa-map-marker-alt mr-3 text-purple-500"></i>
                                    <span>0.2mi from Harvard University</span>
                                </div>
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-bed mr-2 text-purple-500"></i>
                                            <span class="font-medium">Studio</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-bath mr-2 text-purple-500"></i>
                                            <span class="font-medium">1 bath</span>
                                        </div>
                                    </div>
                                    <span class="text-sm font-medium px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-full">Elite</span>
                                </div>
                                <a href="#">
                                    <x-primary-button>
                                    {{__('View Details')}}
                                    </x-primary-button>
                                </a>
                            </div>
                        </div>
                        <br>
                        <!-- Property 3 -->
                        <div class="group card-gradient item ">
                            <div class="relative overflow-hidden h-64">
                                <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                    alt="Modern house" 
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 rounded-full"
                                
                                    style="width:300px;object-fit:cover;border-radius:500px;height:450px;">
                                
                            </div>
                            <div class=" badge-gradient "style="border-radius:20px;text-align:center;height:40px;align-items:center;justify-content:center;">
                                    $3,100/mo
                                </div>
                            <div class="p-8">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-900">The Scholar's Residence</h3>
                                   
                                </div>
                                <div class="flex items-center text-gray-600 dark:text-gray-400 mb-6">
                                    <i class="fas fa-map-marker-alt mr-3 text-purple-500"></i>
                                    <span>0.3mi from MIT</span>
                                </div>
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-bed mr-2 text-purple-500"></i>
                                            <span class="font-medium">4 beds</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-bath mr-2 text-purple-500"></i>
                                            <span class="font-medium">3 baths</span>
                                        </div>
                                    </div>
                                    <span class="text-sm font-medium px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-full">Signature</span>
                                </div>
                                <a href="#">
                                    <x-primary-button>
                                    {{__('View Details')}}
                                    </x-primary-button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="py-20 gradient-primary text-white"style="width:100%;">
                <div class="container mx-auto px-6"style="width:100%;align-item:center;">
                    <div class="text-center mb-16 reveal">
                        <h2 style="color:white; font-weight:semi-bold;text-align:center;">
                            Trusted by <span class="text-white">Thousands</span>
                        </h2>
                        <p style="text-align:center;color:white;">
                            Join the growing community of students who have found their perfect home with UniCrib.
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-12">
                        <div class="text-center reveal">
                            <div class="stat-number mb-9">50,000+</div>
                            <div style="text-align:center;color:white;">Students Served</div>
                        </div>
                        <div class="text-center reveal">
                            <div class="stat-number mb-4">2,500+</div>
                            <div style="text-align:center;color:white;">Properties</div>
                        </div>
                        <div class="text-center reveal">
                            <div class="stat-number mb-4">120+</div>
                            <div style="text-align:center;color:white;">Universities</div>
                        </div>
                        <div class="text-center reveal">
                            <div class="stat-number mb-4">98%</div>
                            <div style="text-align:center;color:white;">Satisfaction</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials -->
            <section class="section-padding bg-white dark:bg-gray-800"style="width:100%;">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-16 reveal">
                        <div class="inline-flex items-center space-x-3 mb-6"style="text-align:center;">
                            
                            <span class="text-gradient-secondary"style="text-align:center;">STUDENT TESTIMONIALS</span>
                            
                        </div>
                        <h2 style="color:white; font-weight:semi-bold;text-align:center;">
                            Students <span class="text-gradient-secondary">Love UniCrib</span>
                        </h2>
                        <p style="text-align:center;color:white;">
                            Hear from students who transformed their housing search with our platform.
                        </p>
                    </div>
                    
                    <div class="containery">
                        <!-- Testimonial 1 -->
                        <div class="card-gradient hover-card item"style="border-radius:10px;">
                            <div style="text-align:center;align-items:center;justify-content:center;display:flex;">
                                <div class="gradient-primary "  style="border-radius:30px;align-items:center;justify-content:center;display:flex;height:40px;">
                                    <span class="font-bold text-white text-lg">JS</span>
                                </div>
                                <div>
                                    <h4 >Jessica Smith</h4>
                                    <p >Stanford University</p>
                                </div>
                            </div>
                            <p >
                                "UniCrib saved me weeks of searching. I found a beautiful apartment within walking distance of campus in just two days!"
                            </p>
                            
                        </div>
                        
                        <!-- Testimonial 2 -->
                        <div class="card-gradient hover-card item"style="border-radius:10px;">
                            <div style="text-align:center;align-items:center;justify-content:center;display:flex;">
                                 <div class="gradient-primary "  style="border-radius:30px;align-items:center;justify-content:center;display:flex;height:40px;">
                                    <span >AR</span>
                                </div>
                                <div>
                                    <h4 >Ahmed Rahman</h4>
                                    <p >MIT</p>
                                </div>
                            </div>
                            <p>
                                "As an international student, I was worried about finding safe housing. UniCrib's verified properties gave me peace of mind."
                            </p>
                           
                        </div>
                        
                        <!-- Testimonial 3 -->
                        <div class="card-gradient hover-card item"style="border-radius:10px;">
                            <div style="text-align:center;align-items:center;justify-content:center;display:flex;">
                                <div class="gradient-primary "  style="border-radius:30px;align-items:center;justify-content:center;display:flex;height:40px;">
                                    <span class="font-bold text-white text-lg">MC</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-gray">Marcus Chen</h4>
                                    <p class="text-gray-500 dark:text-gray-500 text-sm">Harvard University</p>
                                </div>
                            </div>
                            <p class="text-gray-600 dark:text-gray-500 italic mb-6">
                                "The roommate matching feature helped me find perfect housemates. Our house is amazing and the process was seamless."
                            </p>
                           
                        </div>
                    </div>
                </div>
            </section>

            <!-- Final CTA -->
            <section class="py-20 relative overflow-hidden"style="background:linear-gradient(rgba(35,0,70,0.8),rgba(3,56,89,0.8));">
                
                <div class="container mx-auto px-6 relative z-10"style="width:100%;">
                    <div class="max-w-4xl mx-auto text-center">
                        <h2 style="color:white; font-weight:semi-bold;text-align:center;">
                            Ready to Find Your <span class="text-gradient-secondary">Perfect Home?</span>
                        </h2>
                        <p style="text-align:center;color:white;">
                            Join thousands of students who have transformed their university experience with premium, hassle-free housing.
                        </p>
                        <br>
                        <div style="justify-content:center;display:flex;flex-direction:column;">
                            <div>
                                <a href="{{ url('/register') }}" >
                                    <x-primary-button>
                                        {{__('Get Started Now')}}
                                    </x-primary-button>
                                </a>
                            </div>
                            
                            <a href="{{ url('/dashboard') }}"style="color:grey; text-decoration:underline;font-size:18px;margin-top:5px;text-align:center;">
                                Schedule a Tour
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            <footer style="display:flex; background-color:#111827;color:#9ca3af;text-align:center;justify-content:center;align-items:center;" >
                <div class="container" style="text-align:center;justify-content:center;align-items:center;padding-bottom:5%;width:100%;">
                                <div style="text-align:center;justify-content:center;align-items:center;">
                                <div class="w-10 h-10 rounded-full gradient-primary flex items-center justify-center">
                                    <i class="fas fa-building text-white text-lg"></i>
                                </div>
                                <span style="font-size:25px;color:white; font-weight:semi-bold;text-align:center;">UniCrib</span>
                            </div>
                            <p>
                                Premium student accommodations with exceptional service. We're redefining the student housing experience.
                            </p>
                    <div style="display:flex;justify-content:space-between;padding-left:5%;padding-right:5%;align-items:top;text-align:center;overflow:scroll;">
                        <!-- Logo & Description -->
                       <!--<div class="lg:col-span-2">
                            
                            <div class="flex space-x-4">
                                <a href="#" class="w-10 h-10 rounded-full bg-gray-800 hover:gradient-primary flex items-center justify-center text-white transition-all duration-300">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-gray-800 hover:gradient-primary flex items-center justify-center text-white transition-all duration-300">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-gray-800 hover:gradient-primary flex items-center justify-center text-white transition-all duration-300">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-gray-800 hover:gradient-primary flex items-center justify-center text-white transition-all duration-300">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </div>
                        </div>-->
                        
                        <!-- Quick Links -->
                        <div>
                            <h3 class="text-white text-lg font-bold mb-8">Platform</h3>
                            <ul class="space-y-4">
                                <li><a href="{{url('/dashboard')}}" class="hover:text-white transition-colors duration-300">Properties</a></li>
                                <li><a href="{{url('/dashboard')}}" class="hover:text-white transition-colors duration-300">Universities</a></li>
                                <li><a href="{{url('/dashboard')}}" class="hover:text-white transition-colors duration-300">Services</a></li>
                                <li><a href="{{url('/dashboard')}}" class="hover:text-white transition-colors duration-300">Dashboard</a></li>
                            </ul>
                        </div>
                        
                        <!-- Company -->
                        <div>
                            <h3 class="text-white text-lg font-bold mb-8">Company</h3>
                            <ul class="space-y-4">
                                <li><a href="#" class="hover:text-white transition-colors duration-300">About Us</a></li>
                                <li><a href="#" class="hover:text-white transition-colors duration-300">Careers</a></li>
                                <li><a href="#" class="hover:text-white transition-colors duration-300">Blog</a></li>
                                <li><a href="#" class="hover:text-white transition-colors duration-300">Press</a></li>
                            </ul>
                        </div>
                        
                        <!-- Contact -->
                        <div>
                            <h3 class="text-white text-lg font-bold mb-8">Contact</h3>
                            <ul class="space-y-4">
                                <li class="flex items-start">
                                    
                                    <span>Kutus-Kirinyaga</span>
                                </li>
                                <li class="flex items-start">
                                
                                    <span>+254704969006</span>
                                </li>
                                <li class="flex items-start">
                                    
                                    <span>unicrib@gmail.com</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-800 pt-8 flex flex-col lg:flex-row justify-between items-center">
                        <p>&copy; 2026 UniCrib. All rights reserved.</p>
                        <div class="flex space-x-8 mt-4 lg:mt-0">
                            <a href="#" class="hover:text-white transition-colors duration-300">Privacy Policy</a>
                            <a href="#" class="hover:text-white transition-colors duration-300">Terms of Service</a>
                            <a href="#" class="hover:text-white transition-colors duration-300">Cookie Policy</a>
                        </div>
                    </div>
                </div>
            </footer>

            
           
        </div>

    </body>
    <script>
        // Scroll reveal animation
        const revealElements = document.querySelectorAll('.reveal');
        
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        revealElements.forEach(element => {
            revealObserver.observe(element);
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Enhanced hover effects
        document.querySelectorAll('.hover-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-12px)';
                this.style.boxShadow = '0 35px 60px -12px rgba(102, 126, 234, 0.2)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 25px 50px -12px rgba(0, 0, 0, 0.08)';
            });
        });
        
        // Initialize animations on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add staggered animation delays
            revealElements.forEach((element, index) => {
                element.style.transitionDelay = `${index * 0.1}s`;
            });
        });
    </script>
                
</html>
