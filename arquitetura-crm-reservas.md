# Proposta de Arquitetura para CRM de Reservas

## Introdução

Este documento apresenta a análise e a proposta de arquitetura para o desenvolvimento de um **Customer Relationship Management (CRM)** de reservas, conforme solicitado. 

**⚠️ IMPORTANTE:** Esta arquitetura é focada exclusivamente no **sistema CRM/API** que será desenvolvido em **Laravel** (API RESTful e painel administrativo com Inertia/Vue3). O **site público em Astro** será um projeto separado que consumirá a API desenvolvida neste CRM, não fazendo parte do escopo principal deste documento.

A solução CRM está inspirada nas funcionalidades observadas nos sites Vila Amale \[1\], Zandaia \[2\], no plugin MotoPress Hotel Booking \[3\] e nas melhores práticas de plataformas como o Booking.com.

## 1\. Análise Funcional e Tecnológica

A análise dos sistemas de reservas revelou a necessidade de um sistema robusto de gestão de inventário e precificação dinâmica.

| Sistema Analisado | Foco Principal | Funcionalidades Chave |
| :---- | :---- | :---- |
| **Vila Amale & Zandaia** | Experiência do Usuário (UX) e Apresentação Visual | Busca simplificada (Check-in/out), apresentação detalhada de acomodações, políticas claras (check-in/out, restrições). |
| **MotoPress** | Gestão de Propriedades e Flexibilidade | Acomodações ilimitadas, atributos personalizados, regras de preço por temporada, serviços extras, sincronização iCal. |
| **Booking.com** | Escala e Gestão de Canais | Perfis de usuário (Hóspede/Host), sistema de reviews, notificações automatizadas, gestão de inventário (bloqueio de datas). |

A escolha da *stack* **Laravel/Inertia/Vue3** para o CRM é estratégica. O Laravel oferece uma API RESTful robusta e o ecossistema Inertia/Vue3 proporciona uma experiência de usuário rica e reativa para o painel administrativo.

**Sobre o site público:** O site em Astro será desenvolvido como um projeto independente que consumirá esta API Laravel. Esta separação permite que cada sistema tenha seu próprio ciclo de desenvolvimento, deploy e manutenção, enquanto o Astro garante um frontend público ultra rápido, essencial para o SEO e a experiência do cliente \[4\].

## 2\. Entidades e Relacionamentos do Banco de Dados

A modelagem de dados é o pilar do CRM, suportando a complexidade das reservas e da precificação. A tabela a seguir detalha as entidades principais e seus relacionamentos:

| Entidade | Descrição | Relacionamentos Principais |
| :---- | :---- | :---- |
| **User** | Usuários do sistema (Admin, Staff, Hóspede). | Possui muitas `Booking`. |
| **Property** | Representa o estabelecimento (ex: Vila Amale). | Possui muitas `AccommodationType`. |
| **AccommodationType** | Categoria da acomodação (ex: Chalé, Suíte Master). | Pertence a `Property`, possui muitas `Accommodation`. |
| **Accommodation** | Unidade física específica (ex: Chalé 01, Quarto 202). | Pertence a `AccommodationType`, possui muitas `Booking`. |
| **Amenity** | Comodidades (ex: Wi-Fi, Ar Condicionado, Jacuzzi). | Muitos-para-muitos com `AccommodationType`. |
| **Season** | Períodos de tempo (ex: Alta Temporada, Feriado). | Possui muitas `Rate`. |
| **Rate** | Regras de preço baseadas em temporada e tipo. | Pertence a `AccommodationType` e `Season`. |
| **Booking** | Registro da reserva realizada pelo cliente. | Pertence a `User` (Hóspede) e `Accommodation`. |
| **Service** | Serviços extras (ex: Café da manhã, Translado). | Muitos-para-muitos com `Booking`. |
| **Payment** | Transações financeiras vinculadas à reserva. | Pertence a `Booking`. |

**Nota sobre Precificação:** A entidade `Rate` é crucial para implementar a precificação dinâmica, permitindo que o sistema calcule o preço total da reserva com base nas datas (que se enquadram em diferentes `Season`s) e no tipo de acomodação (`AccommodationType`).

## 3\. Fluxos de Processos de Negócio

Os fluxos definem a lógica de interação entre os componentes do sistema.

### 3.1. Fluxo de Reserva (Consumo da API por Clientes Externos)

**Nota:** Este fluxo descreve como clientes externos (como o site Astro) interagem com a API do CRM. O desenvolvimento do site em si não está incluído no escopo deste projeto CRM.

1. **Busca e Disponibilidade:** Cliente externo consulta a API do Laravel com datas e número de hóspedes. A API utiliza as entidades `Accommodation`, `Season` e `Rate` para calcular disponibilidade e preços.  
2. **Seleção e Serviços:** Cliente externo envia acomodação escolhida e `Service`s extras para processamento.  
3. **Criação da Reserva:** Cliente externo envia dados completos para a API do Laravel, que cria a `Booking` com status **Pendente** e o `Payment` inicial.  
4. **Confirmação:** Após confirmação do pagamento (via webhook ou validação), a API atualiza o status da `Booking` para **Confirmada** e notifica o cliente externo.

### 3.2. Fluxo de Gestão (CRM Laravel/Inertia)

Este fluxo é exclusivo para o administrador e staff:

1. **Configuração:** O administrador gerencia `Property`, `AccommodationType`, `Amenity`, `Season` e `Rate`.  
2. **Gestão de Reservas:** O staff visualiza, filtra e atualiza o status das `Booking`s.  
3. **Check-in/Check-out:** O staff registra o pagamento do saldo restante e finaliza a reserva.  
4. **Sincronização (iCal):** O sistema expõe e consome feeds iCal para manter a disponibilidade sincronizada com canais externos (Booking.com, Airbnb).

## Conclusão

A arquitetura proposta fornece uma base sólida para o desenvolvimento do **sistema CRM/API** em Laravel. A abordagem de API-first permite que múltiplos clientes (sites, aplicativos móveis, sistemas terceiros) possam consumir os dados de forma padronizada e segura.

**Separação de Responsabilidades:**
- **Projeto CRM (este documento):** Sistema completo de gestão em Laravel com API RESTful e painel administrativo
- **Projetos Clientes:** Sites, apps ou outros sistemas que consomem a API (desenvolvidos separadamente)

Esta arquitetura garante escalabilidade, facilidade de manutenção e permite que cada sistema evolua de forma independente.

---

## Referências

\[1\] Vila Amalê. *Vila Amalê*. [https://vilaamale.com.br/](https://vilaamale.com.br/) \[2\] Zandaia Eco Pousada. *Zandaia Eco Pousada*. [https://zandaia.com.br/](https://zandaia.com.br/) \[3\] MotoPress. *WordPress Hotel Booking Plugin*. [https://motopress.com/products/hotel-booking/](https://motopress.com/products/hotel-booking/) \[4\] Prioridade de SEO e Indexação na Estrutura Base de Plataformas de Negócios. *Conhecimento Interno*.  
