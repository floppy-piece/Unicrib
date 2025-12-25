<style>
  .button1{
display:block;
margin-left:auto;
margin-right:auto;
  padding: 12px 28px;
  background: linear-gradient(
    90deg,
    rgba(255, 255, 255, 0.9) 0%,
    rgba(245, 243, 255, 0.9) 50%,
    rgba(233, 213, 255, 0.9) 100%
  );
  border: 1px solid #e9d5ff;
  border-radius: 8px;
  color: #7c3aed;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 2px 10px rgba(124, 58, 237, 0.1);
}

.button1:hover {
  transform: translateY(-1px);
  background: linear-gradient(
    90deg,
    white 0%,
    #f5f3ff 50%,
    #e9d5ff 100%
  );
  box-shadow: 0 4px 15px rgba(124, 58, 237, 0.15);
}

.button1:active {
  transform: translateY(0);
  background: linear-gradient(
    90deg,
    #f8f7ff 0%,
    #f1ebff 50%,
    #e5d5fa 100%
  );
}
</style>
<button {{ $attributes->merge(['type' => 'submit','class' =>'button1']) }}>
    {{ $slot }}
</button>
