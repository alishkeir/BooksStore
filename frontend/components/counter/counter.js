import { Amount, Counter, CounterButtonMinus, CounterButtonPlus } from '@components/counter/counter.styled';

export default function CounterComp(props) {
  let { bookId, value = 0, onclickPlus = () => {}, onclickMinus = () => {} } = props;

  return (
    <Counter>
      <CounterButtonMinus onClick={(e) => onclickMinus(e, bookId)}>-</CounterButtonMinus>
      <Amount>{value}</Amount>
      <CounterButtonPlus onClick={(e) => onclickPlus(e, bookId)}>+</CounterButtonPlus>
    </Counter>
  );
}
