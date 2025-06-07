<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\Testimonial;
use App\Models\Faq;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create Plans
        Plan::create([
            'name' => 'Básico',
            'description' => 'Ideal para quem está começando',
            'seals_limit' => 1,
            'contracts_limit' => 1,
            'monthly_price' => 29.90,
            'six_months_price' => 99.90,
            'yearly_price' => 199.90,
            'is_active' => true
        ]);

        Plan::create([
            'name' => 'Intermediário',
            'description' => 'Para usuários com necessidades moderadas',
            'seals_limit' => 3,
            'contracts_limit' => 3,
            'monthly_price' => 49.90,
            'six_months_price' => 199.90,
            'yearly_price' => 299.90,
            'is_active' => true
        ]);

        Plan::create([
            'name' => 'Plus',
            'description' => 'Acesso completo e ilimitado',
            'seals_limit' => null, // unlimited
            'contracts_limit' => null, // unlimited
            'monthly_price' => 69.90,
            'six_months_price' => 299.90,
            'yearly_price' => 499.90,
            'is_active' => true
        ]);

        // Create Testimonials
        Testimonial::create([
            'name' => 'Maria Silva',
            'occupation' => 'Empresária',
            'content' => 'O Trust-me me deu a segurança que eu precisava para formalizar meus contratos. Excelente plataforma!',
            'rating' => 5,
            'is_active' => true
        ]);

        Testimonial::create([
            'name' => 'João Santos',
            'occupation' => 'Freelancer',
            'content' => 'Finalmente posso trabalhar com tranquilidade sabendo que meus documentos estão verificados.',
            'rating' => 5,
            'is_active' => true
        ]);

        Testimonial::create([
            'name' => 'Ana Costa',
            'occupation' => 'Consultora',
            'content' => 'A verificação de identidade é rápida e confiável. Recomendo para todos!',
            'rating' => 5,
            'is_active' => true
        ]);

        // Create FAQs
        Faq::create([
            'question' => 'Como funciona a verificação de documentos?',
            'answer' => 'Nossa plataforma verifica seus documentos através de órgãos oficiais, garantindo a autenticidade das informações.',
            'order' => 1,
            'is_active' => true
        ]);

        Faq::create([
            'question' => 'Quanto tempo leva para verificar um documento?',
            'answer' => 'O processo de verificação geralmente leva de 24 a 48 horas úteis.',
            'order' => 2,
            'is_active' => true
        ]);

        Faq::create([
            'question' => 'Posso cancelar minha assinatura a qualquer momento?',
            'answer' => 'Sim, você pode cancelar sua assinatura a qualquer momento através do seu painel de controle.',
            'order' => 3,
            'is_active' => true
        ]);

        Faq::create([
            'question' => 'Os contratos têm validade jurídica?',
            'answer' => 'Sim, todos os contratos criados em nossa plataforma têm validade jurídica e são reconhecidos legalmente.',
            'order' => 4,
            'is_active' => true
        ]);

        Faq::create([
            'question' => 'Como posso alterar meu plano?',
            'answer' => 'Você pode alterar seu plano a qualquer momento através da área de assinaturas em seu perfil.',
            'order' => 5,
            'is_active' => true
        ]);
    }
}
