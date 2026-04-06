New UniCrib contact message

From: {{ $data['name'] }} ({{ $data['email'] }})
Role: {{ $data['role'] ?? 'N/A' }}

Subject: {{ $data['subject'] }}

Message:
{{ $data['message'] }}

IP: {{ $data['ip'] ?? 'N/A' }}
User Agent: {{ $data['user_agent'] ?? 'N/A' }}
