<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PersonalInfo;
use App\Models\Skill;
use App\Models\Service;
use App\Models\Stat;
use App\Models\Project;
use App\Models\ResumeItem;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Personal Info
        PersonalInfo::create([
            'name'          => 'Mohamed Reda Keshk',
            'profile'       => 'Backend Developer',
            'email'         => 'm7mdreda74@gmail.com',
            'phone'         => '+201099670724',
            'bio'           => 'I am a motivated backend developer with hands-on experience in creating web applications and APIs using Laravel and PHP. I have successfully completed several training programs, enhancing my skills in database management, RESTful services, and teamwork. Eager to contribute to innovative backend systems, I continuously seek opportunities to improve my technical expertise and apply best practices in software development.',
            'profile_image' => 'assets/img/profile-img.png',
            'hero_image'    => 'assets/img/hero-img.png',
        ]);

        // 2. Skills — from CV Technical Skills section
        Skill::create(['name' => 'PHP',        'percentage' => 90]);
        Skill::create(['name' => 'Laravel',    'percentage' => 90]);
        Skill::create(['name' => 'MySQL / SQL', 'percentage' => 85]);
        Skill::create(['name' => 'JavaScript', 'percentage' => 75]);
        Skill::create(['name' => 'RESTful APIs', 'percentage' => 90]);
        Skill::create(['name' => 'Git & GitHub', 'percentage' => 80]);
        Skill::create(['name' => 'C#',         'percentage' => 60]);
        Skill::create(['name' => 'Python',     'percentage' => 60]);

        // 3. Services — derived from proven project work in CV
        Service::create([
            'title'       => 'Website Development',
            'description' => 'Building responsive, modern websites and web applications, including e-commerce platforms, dynamic portals, and custom web systems using PHP and Laravel.',
            'icon'        => 'bi bi-globe',
        ]);
        Service::create([
            'title'       => 'RESTful API Development',
            'description' => 'Designing and building structured, secure RESTful APIs with Laravel Sanctum authentication, versioning, and clean endpoint architecture for web and mobile integrations.',
            'icon'        => 'bi bi-code-slash',
        ]);
        Service::create([
            'title'       => 'Custom Admin Dashboards',
            'description' => 'Building feature-rich admin panels and business control panels with dynamic resource managers, interactive reporting, and fully controlled content workflows.',
            'icon'        => 'bi bi-layout-text-sidebar-reverse',
        ]);
        Service::create([
            'title'       => 'SaaS & Multi-Tenant Systems',
            'description' => 'Engineering multi-database SaaS platforms with automated tenant onboarding, isolated environments, subdomain routing, and centralized super-admin portals.',
            'icon'        => 'bi bi-server',
        ]);
        Service::create([
            'title'       => 'Role-Based Access Control',
            'description' => 'Implementing fine-grained RBAC systems using Spatie Permissions to secure sensitive operations and APIs across complex admin hierarchies.',
            'icon'        => 'bi bi-shield-lock',
        ]);
        Service::create([
            'title'       => 'Database Design & Optimization',
            'description' => 'Designing efficient relational database schemas, writing optimized queries, and implementing caching strategies for high-performance applications.',
            'icon'        => 'bi bi-diagram-3',
        ]);

        // 4. Stats
        Stat::create(['label' => 'Projects Completed', 'count' => 8]);
        Stat::create(['label' => 'Training Programs',  'count' => 3]);
        Stat::create(['label' => 'GitHub Repositories', 'count' => 15]);
        Stat::create(['label' => 'Months Experience',  'count' => 33]);

        // 5. Projects — real projects from CV
        Project::create([
            'title'        => 'Serv5 Company Website',
            'category'     => 'web',
            'description'  => 'Built scalable RESTful APIs (v1) with Laravel Sanctum, a Filament admin panel with custom Page Builder, dynamic pricing engine, ATS careers portal, quotation engine, RBAC with Spatie, and automated cache invalidation.',
            'image'        => 'assets/img/portfolio/serv5.png',
            'details_link' => 'https://serv5.com.eg/',
        ]);
        Project::create([
            'title'        => 'MyRestaurant SaaS Platform',
            'category'     => 'saas',
            'description'  => 'Multi-tenant SaaS with database-per-tenant isolation, automated onboarding (migrations + subdomain config), tri-level RBAC (Super Admin / Tenant Admin / Staff), real-time order processing, digital menu, and inventory tracking.',
            'image'        => 'assets/img/portfolio/restaurant.png',
            'details_link' => '#',
        ]);
        Project::create([
            'title'        => 'Glovy — Parkinson\'s Aid Platform',
            'category'     => 'api',
            'description'  => 'A platform helping Parkinson\'s patients, integrating a mobile app, ML-based disease diagnosis models, and real-time Firebase sensor readings via a Laravel REST backend.',
            'image'        => 'assets/img/portfolio/glovy.jpg',
            'details_link' => 'https://drive.google.com/file/d/1pMkFEnCtR4ISjgLO45hiWM6bhOY3eqGQ/view?usp=sharing',
        ]);
        Project::create([
            'title'        => 'Grand Travel — Luxury Travel Platform',
            'category'     => 'web',
            'description'  => 'Luxury travel platform featuring a full admin dashboard for managing tour packages, bookings, multi-category tours, and multi-region business operations with localized currencies across 10+ countries.',
            'image'        => 'assets/img/portfolio/grand-travel.png',
            'details_link' => 'https://grandtravel1.com/',
        ]);

        Project::create([
            'title'        => 'Mobile-Shopee E-Commerce',
            'category'     => 'web',
            'description'  => 'A PHP/HTML/CSS/JS e-commerce website for a mobile shop with CRUD operations for mobiles and categories.',
            'image'        => 'assets/img/portfolio/mobile-shopee.png',
            'details_link' => 'https://github.com/m7mdreda74/E-CommerceMobileShopee',
        ]);

        // 6. Resume Items
        // --- Education ---
        ResumeItem::create([
            'type'         => 'education',
            'title'        => 'Bachelor of Computer & Information Sciences — Information Systems',
            'organization' => 'Mansoura University',
            'duration'     => '2020 – 2024',
            'description'  => 'Studied core computer science fundamentals with a specialization in Information Systems, covering database design, software engineering, networking, and programming paradigms.',
        ]);
        ResumeItem::create([
            'type'         => 'education',
            'title'        => 'Fullstack Training Program',
            'organization' => 'ITI (Information Technology Institute) — Mansoura',
            'duration'     => 'Aug 2024 – Sep 2024',
            'description'  => 'Intensive fullstack training covering HTML, CSS, JavaScript, MySQL, PHP, and Laravel, with hands-on project work.',
        ]);
        ResumeItem::create([
            'type'         => 'education',
            'title'        => 'Backend Training Program',
            'organization' => 'NTI (National Telecommunications Institute) — Mansoura',
            'duration'     => 'May 2024 – Aug 2024',
            'description'  => 'Focused training in PHP, SQL, and Laravel backend development including REST API design and database management.',
        ]);
        ResumeItem::create([
            'type'         => 'education',
            'title'        => 'Back-End Development Course',
            'organization' => 'CCIC — Mansoura University',
            'duration'     => 'Jul 2023 – Oct 2023',
            'description'  => 'Training course covering Dynamic Language (PHP), MySQL, and the Laravel framework with practical project application.',
        ]);

        // --- Experience ---
        ResumeItem::create([
            'type'         => 'experience',
            'title'        => 'Backend Developer',
            'organization' => 'Serv5',
            'duration'     => 'Apr 2025 – Present',
            'description'  => 'Building and maintaining scalable RESTful APIs, a Filament-powered admin dashboard with a custom Page Builder, dynamic pricing and quotation engines, an ATS careers portal, RBAC with Spatie, and cache-invalidation strategies for improved performance.',
        ]);
        ResumeItem::create([
            'type'         => 'experience',
            'title'        => 'Backend Developer',
            'organization' => 'Grand Travel',
            'duration'     => 'Jun 2024 – Sep 2024',
            'description'  => 'Developed the core backend with PHP & Laravel to manage tour packages, bookings, and multi-region business flows, designed the MySQL database structure for multi-currency handling, and implemented a Filament-based CMS with dynamic filters for specialized tours.',
        ]);
        ResumeItem::create([
            'type'         => 'experience',
            'title'        => 'Back-End Member',
            'organization' => 'CIS Team MU — Mansoura University',
            'duration'     => 'Nov 2022 – Dec 2023',
            'description'  => 'Contributed as a backend developer in a student-activity technology team, collaborating on web projects and strengthening PHP/Laravel skills in a team environment.',
        ]);
    }
}
