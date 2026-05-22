<div id="chat-widget" style="position:fixed;bottom:90px;right:20px;z-index:900;font-family:Inter,system-ui,sans-serif;">
  {{-- Chat button --}}
  <button onclick="toggleChat()" id="chat-btn" style="width:52px;height:52px;border-radius:50%;background:var(--color-warm);color:#fff;border:none;cursor:pointer;box-shadow:0 4px 20px rgba(0,0,0,0.15);display:flex;align-items:center;justify-content:center;font-size:22px;transition:transform 0.2s;">
    💬
  </button>

  {{-- Chat window --}}
  <div id="chat-window" style="display:none;position:absolute;bottom:62px;right:0;width:360px;max-height:480px;background:#fff;border-radius:16px;box-shadow:0 8px 40px rgba(0,0,0,0.12);overflow:hidden;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;background:var(--color-warm);color:#fff;font-weight:600;font-size:15px;">
      <span>💬 KATUISCIA</span>
      <button onclick="toggleChat()" style="background:none;border:none;color:#fff;font-size:20px;cursor:pointer;line-height:1;">&times;</button>
    </div>
    <div id="chat-messages" style="flex:1;overflow-y:auto;padding:16px;min-height:250px;max-height:300px;display:flex;flex-direction:column;gap:10px;background:#faf7f2;font-size:14px;line-height:1.5;">
      <div style="align-self:flex-start;background:#fff;padding:10px 14px;border-radius:12px 12px 12px 4px;max-width:85%;color:var(--color-dark);box-shadow:0 1px 4px rgba(0,0,0,0.04);">
        Bonjour ! Je suis l'assistant KATUISCIA. Comment puis-je vous aider ?
      </div>
    </div>
    <div style="display:flex;gap:8px;padding:12px;border-top:1px solid #ede4db;background:#fff;">
      <input id="chat-input" type="text" placeholder="Votre message..." style="flex:1;padding:10px 14px;border:2px solid #d1d5db;border-radius:10px;font-size:14px;outline:none;" onkeydown="if(event.key==='Enter')sendMessage()">
      <button onclick="sendMessage()" style="padding:10px 16px;background:var(--color-warm);color:#fff;border:none;border-radius:10px;cursor:pointer;font-weight:500;font-size:14px;">➤</button>
    </div>
  </div>
</div>

<style>#chat-window{display:none;}#chat-window.open{display:flex;flex-direction:column;}</style>
<script>
var chatOpen = false;
function toggleChat() {
  chatOpen = !chatOpen;
  var w = document.getElementById('chat-window');
  var b = document.getElementById('chat-btn');
  if (chatOpen) {
    w.classList.add('open');
    w.style.display = 'flex';
    b.textContent = '✕';
    b.style.transform = 'rotate(90deg)';
    document.getElementById('chat-input').focus();
  } else {
    w.classList.remove('open');
    w.style.display = 'none';
    b.textContent = '💬';
    b.style.transform = 'rotate(0deg)';
  }
}
function sendMessage() {
  var input = document.getElementById('chat-input');
  var msg = input.value.trim();
  if (!msg) return;
  appendMessage(msg, 'user');
  input.value = '';
  appendMessage('...', 'bot-typing');
  var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
  fetch('{{ url('/api/chat') }}', {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
    body: JSON.stringify({message: msg})
  }).then(r => r.json()).then(data => {
    document.querySelector('.bot-typing')?.remove();
    appendMessage(data.reply, 'bot');
  }).catch(() => {
    document.querySelector('.bot-typing')?.remove();
    appendMessage('Désolé, une erreur est survenue.', 'bot');
  });
}
function appendMessage(text, type) {
  var div = document.createElement('div');
  var isUser = type === 'user';
  div.style.cssText = 'align-self:'+(isUser?'flex-end':'flex-start')+';background:'+(isUser?'var(--color-warm);color:#fff':'#fff;color:var(--color-dark)')+';padding:10px 14px;border-radius:'+(isUser?'12px 12px 4px 12px':'12px 12px 12px 4px')+';max-width:85%;box-shadow:0 1px 4px rgba(0,0,0,0.04);font-size:14px;';
  div.textContent = text;
  if (type === 'bot-typing') { div.classList.add('bot-typing'); div.textContent = 'KATUISCIA réfléchit...'; div.style.background = '#fff'; div.style.color = 'var(--color-text-muted)'; div.style.fontStyle = 'italic'; }
  document.getElementById('chat-messages').appendChild(div);
  div.scrollIntoView({behavior:'smooth'});
}
</script>
