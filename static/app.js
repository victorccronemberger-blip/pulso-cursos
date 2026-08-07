(() => {
  const api = window.ACADEMY_API_URL.replace(/\/$/, '');
  const app = document.querySelector('#app'), account = document.querySelector('#account');
  const dialog = document.querySelector('#login-dialog'), form = document.querySelector('#login-form');
  const tokenKey = 'academy_access_token';
  const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const price = value => value === 'Free' ? 'Grátis' : value;
  async function request(path, options = {}) {
    const token = sessionStorage.getItem(tokenKey);
    const res = await fetch(api + path, { ...options, headers: { Accept: 'application/json', ...(options.body ? {'Content-Type':'application/json'} : {}), ...(token ? {Authorization:`Bearer ${token}`} : {}), ...(options.headers || {}) } });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(data.message || 'Não foi possível concluir esta ação.');
    return data;
  }
  const card = c => `<article class="card"><img src="${esc(c.thumbnail)}" alt="${esc(c.title)}"><div><span class="eyebrow">${esc(c.level || 'CURSO COMPLETO')}</span><h2>${esc(c.title)}</h2><p>${esc(c.short_description)}</p><strong>${esc(price(c.price))}</strong><a class="button" href="#/course/${encodeURIComponent(c.slug)}">Ver curso</a></div></article>`;
  async function courses() {
    app.innerHTML='<p class="loading">Carregando cursos…</p>';
    try { const {data}=await request('/frontend/courses'); app.innerHTML=`<section class="hero"><p>PREPARAÇÃO PARA CERTIFICAÇÕES</p><h1>Estude. Pratique. Passe.</h1><p>Conteúdo completo e simulados para a sua aprovação.</p></section><section class="catalog"><h2>Cursos em destaque</h2><div class="grid">${data.map(card).join('')}</div></section>`; } catch(e) { app.innerHTML=`<p class="error-page">${esc(e.message)} Confira se a API está publicada.</p>`; }
  }
  async function course(slug) {
    app.innerHTML='<p class="loading">Carregando curso…</p>';
    try { const {data:c}=await request('/frontend/courses/'+encodeURIComponent(slug)); app.innerHTML=`<section class="course"><img src="${esc(c.banner || c.thumbnail)}" alt="${esc(c.title)}"><div><p class="eyebrow">${esc(c.level || 'CURSO')}</p><h1>${esc(c.title)}</h1><p>${esc(c.short_description)}</p><p class="price">${esc(price(c.price))}</p><button id="buy" class="button">${c.is_paid ? 'Comprar agora' : 'Inscrever-se'}</button></div></section><section class="content"><h2>Sobre este curso</h2><p>${esc(c.description).replace(/\n/g,'<br>')}</p></section>`; document.querySelector('#buy').onclick=()=>checkout(c.id); } catch(e) { app.innerHTML=`<p class="error-page">${esc(e.message)}</p>`; }
  }
  async function checkout(id) { if (!sessionStorage.getItem(tokenKey)) return dialog.showModal(); try { const {checkout_url}=await request(`/frontend/checkout/course/${id}`,{method:'POST'}); location.assign(checkout_url); } catch(e) { alert(e.message); } }
  async function updateAccount() { if (!sessionStorage.getItem(tokenKey)) {account.textContent='Entrar';account.onclick=()=>dialog.showModal();return;} try {const {data}=await request('/frontend/me'); account.textContent=`Olá, ${data.name.split(' ')[0]}`; account.onclick=()=>{sessionStorage.removeItem(tokenKey);updateAccount();};} catch (_) {sessionStorage.removeItem(tokenKey);account.textContent='Entrar';account.onclick=()=>dialog.showModal();} }
  form.onsubmit=async e=>{e.preventDefault();const error=document.querySelector('#login-error');error.textContent='';try {const r=await request('/login',{method:'POST',body:JSON.stringify(Object.fromEntries(new FormData(form)))});sessionStorage.setItem(tokenKey,r.token);dialog.close();form.reset();updateAccount();} catch(err){error.textContent=err.message;}};
  document.querySelector('#close-login').onclick=()=>dialog.close();
  function route(){const [,kind,slug]=location.hash.split('/');kind==='course'&&slug?course(slug):courses();} window.addEventListener('hashchange',route);updateAccount();route();
})();
